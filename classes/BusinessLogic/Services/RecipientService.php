<?php

namespace CleverReachIntegration\BusinessLogic\Services;

use CleverReachIntegration\BusinessLogic\HTTP\Proxy;
use CleverReachIntegration\BusinessLogic\Validators\RecipientValidator;
use CleverReachIntegration\DataAccessLayer\APIClientRepository;
use Exception;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use CleverReachIntegration\Presentation\Models\Group;
use CleverReachIntegration\Presentation\Models\Recipient;
use CleverReachIntegration\Presentation\Models\RecipientOrder;
use CleverReachIntegration\DataAccessLayer\RecipientRepository;
use CleverReachIntegration\Presentation\Models\SyncStatus;
use PrestaShopDatabaseException;
use PrestaShopException;

/**
 * Class RecipientService
 */
class RecipientService
{
    /**
     * @var int
     */
    const SYNCHRONIZATION_BATCH = 12;
    /**
     * @var APIClientRepository
     */
    private $apiClientRepository;
    /**
     * @var Proxy
     */
    private $proxy;
    /**
     * @var string
     */
    private $token;
    /**
     * @var RecipientRepository
     */
    private $recipientRepository;

    /**
     * Initializes recipient repository, proxy and access token
     */
    public function __construct()
    {
        $this->proxy = new Proxy();
        $this->apiClientRepository = new APIClientRepository();
        $this->recipientRepository = new RecipientRepository();
        $this->token = APIClientRepository::returnAccessToken();
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws Exception
     */
    public function synchronize()
    {
        $this->changeSyncStatus(SyncStatus::IN_PROGRESS);
        $group = $this->getApiGroup();
        $this->prepareRecipients($group);
    }

    /**
     * @param $id
     * @return array|bool|\mysqli_result|\PDOStatement|resource|null
     * @throws PrestaShopDatabaseException
     */
    public function getSinglePrestaShopCustomer($id)
    {
        return $this->recipientRepository->getSingleCustomer($id);
    }

    /**
     * @param $customerId
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function synchronizeCreatedCustomer($customerId)
    {
        $customer = $this->getSinglePrestaShopCustomer($customerId);
        $recipient = $this->createRecipient(array(), $customer);
        $recipientJSON = json_encode($recipient->getArray());
    }

    /**
     * @param string $status
     */
    private function changeSyncStatus($status)
    {
        $this->apiClientRepository->changeSyncStatus($status);
    }

    /**
     * @return mixed
     */
    private function getApiGroup()
    {
        $shopName = $this->recipientRepository->getShopName();
        $groupModel = new Group($shopName, 'Prestashop customers');
        $fields = json_encode($groupModel->getArray());

        return ($this->groupExists($shopName)) ?:
            $this->proxy->postWithHTTPHeader('https://rest.cleverreach.com/v3/groups.json', $fields, $this->token);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    private function groupExists($name)
    {
        $groups = $this->proxy->getWithHTTPHeader('https://rest.cleverreach.com/v3/groups.json', $this->token);
        foreach ($groups as $group) {
            if ($group['name'] === $name) {
                return $group;
            }
        }

        return null;
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return array|bool|\mysqli_result|\PDOStatement|resource|null
     * @throws PrestaShopDatabaseException
     */
    private function getPrestaShopCustomers($offset, $limit)
    {
        return $this->recipientRepository->getPrestaShopCustomers($offset, $limit);
    }

    /**
     * @param $group
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws Exception
     */
    private function prepareRecipients($group)
    {
        $numberOfCustomers = (int)$this->recipientRepository->getCustomerNumber();

        for ($i = 1; $i <= ($numberOfCustomers / self::SYNCHRONIZATION_BATCH); $i++) {
            $customers = $this->getPrestaShopCustomers(($i - 1) * self::SYNCHRONIZATION_BATCH, self::SYNCHRONIZATION_BATCH);
            $recipients = array();

            foreach ($customers as $customer) {
                $orders = $this->recipientRepository->getCustomerOrders($customer['id_customer']);
                $recipient = $this->createRecipient($orders, $customer);
                $recipients[] = $recipient;
            }

            $this->synchronizeWithBatch($recipients, $group, $i);
        }
    }

    /**
     * @param array $orders
     * @param $customer
     * @return Recipient
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function createRecipient($orders, $customer)
    {
        $recipientOrders = array();

        foreach ($orders as $order) {
            $customerOrder = new Order($order['id_order']);
            $products = $customerOrder->getProducts();
            foreach ($products as $product) {
                $recipientOrder = new RecipientOrder($order['reference'], $product['id_product'], $product['product_name'], $product['product_price'],
                    $order['iso_code'], (int)$product['product_quantity'], '');
                $recipientOrders[] = $recipientOrder;
            }
        }

        $globalAttributes = RecipientValidator::validateRecipientGlobalAttributes($customer);
        $tags = $this->setRecipientTags($customer['id_customer']);

        return new Recipient($customer['email'], (explode(" ", $customer['date_add']))[0], (explode(" ", $customer['date_add']))[0],
            $customer['shop'], array(), $globalAttributes, $tags, $recipientOrders);
    }

    /**
     * @param $customerId
     * @return array
     * @throws PrestaShopDatabaseException
     */
    private function setRecipientTags($customerId)
    {
        $tags = array();
        $groupIds = $this->recipientRepository->getCustomerGroups($customerId);
        foreach ($groupIds as $groupId) {
            $groupName = $this->recipientRepository->getGroupById((int)$groupId['id_group']);
            $tags[] = $groupName;
        }

        return $tags;
    }

    /**
     * @param $recipients
     * @param $group
     * @throws Exception
     */
    private function synchronizeWithBatch($recipients, $group, $batchNumber)
    {
        for ($i = 1; $i <= self::SYNCHRONIZATION_BATCH; $i++) {
            if ($batchNumber === 1 && $i === 1) {
                $this->apiClientRepository->changeBatchUpdateTime();
            }
            if ($i % self::SYNCHRONIZATION_BATCH === 0) {
                if ($this->getTimeDifferenceInSeconds() < 30) {
                    $this->apiClientRepository->changeBatchUpdateTime();
                } else {
                    $this->changeSyncStatus(SyncStatus::ERROR);
                    break;
                }
            }

            if ($batchNumber === 10 && $i === self::SYNCHRONIZATION_BATCH) {
                $this->changeSyncStatus(SyncStatus::DONE);
            }

            $recipientJSON = json_encode($recipients[$i - 1]->getArray());
            $this->proxy->postWithHTTPHeader("https://rest.cleverreach.com/v3/groups.json/" . $group['id'] . "/receivers", $recipientJSON, $this->token);
        }
    }

    /**
     * @throws Exception
     */
    private function getTimeDifferenceInSeconds()
    {
        $lastBatchUpdatedTime = new \DateTime($this->apiClientRepository->getLastBatchUpdatedTime());
        $now = new \DateTime(date("Y-m-d H:i:s"));

        return $now->getTimestamp() - $lastBatchUpdatedTime->getTimestamp();
    }
}