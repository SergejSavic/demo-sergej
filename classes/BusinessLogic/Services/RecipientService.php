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
    const SYNCHRONIZATION_BATCH_LENGTH = 12;
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
     * @var array
     */
    private $group;

    /**
     * Initializes recipient repository, proxy and access token
     */
    public function __construct()
    {
        $this->proxy = new Proxy();
        $this->apiClientRepository = new APIClientRepository();
        $this->recipientRepository = new RecipientRepository();
        $this->token = APIClientRepository::returnAccessToken();
        $this->group = $this->getApiGroup();
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws Exception
     */
    public function synchronize()
    {
        $this->changeSyncStatus(SyncStatus::IN_PROGRESS);
        $group = $this->group;
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
     * @throws PrestaShopDatabaseException
     */
    public function updateCreatedCustomerGroups($customerId, $groups)
    {
        $customer = $this->getSinglePrestaShopCustomer($customerId);
        $email = $customer[0]['email'];
        $tags = $this->createRecipientTags($groups);
        $group = $this->group;
        $updateData = array("tags" => $tags);
        $this->proxy->put('https://rest.cleverreach.com/v3/groups.json/' . $group['id'] . "/receivers/" . $email, $updateData, $this->token);
    }

    /**
     * @param $customerId
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function synchronizeCreatedOrUpdatedCustomer($customerId)
    {
        $customer = $this->getSinglePrestaShopCustomer($customerId);
        $recipient = $this->createRecipient(array(), $customer[0]);
        $recipientJSON = json_encode($recipient->getArray());
        $group = $this->group;
        $this->proxy->postWithHTTPHeader('https://rest.cleverreach.com/v3/groups.json/' . $group['id'] . "/receivers", $recipientJSON, $this->token);
    }

    /**
     * @throws PrestaShopDatabaseException
     */
    public function synchronizeUpdatedCustomer($customerId, $email)
    {
        $customer = $this->getSinglePrestaShopCustomer($customerId);
        if ($customer[0]['email'] === $email) {
            $name = "";
        }
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

        for ($i = 1; $i <= (ceil($numberOfCustomers / self::SYNCHRONIZATION_BATCH_LENGTH)); $i++) {
            $customers = $this->getPrestaShopCustomers(($i - 1) * self::SYNCHRONIZATION_BATCH_LENGTH, self::SYNCHRONIZATION_BATCH_LENGTH);
            $recipients = array();

            foreach ($customers as $customer) {
                $orders = $this->recipientRepository->getCustomerOrders($customer['id_customer']);
                $recipient = $this->createRecipient($orders, $customer);
                $recipients[] = $recipient;
            }

            $this->synchronizeWithBatch($recipients, $group, $i, $numberOfCustomers);
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

        return new Recipient($customer['email'], strtotime($customer['date_add']), strtotime($customer['date_add']),
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
     * @param $tagIds
     * @return array
     */
    private function createRecipientTags($tagIds)
    {
        $tags = array();
        foreach ($tagIds as $tagId) {
            $tag = $this->recipientRepository->getGroupById($tagId);
            $tags[] = $tag;
        }

        return $tags;
    }

    /**
     * @param $recipients
     * @param $group
     * @param $batchNumber
     * @param $numberOfCustomers
     * @throws Exception
     */
    private function synchronizeWithBatch($recipients, $group, $batchNumber, $numberOfCustomers)
    {
        for ($i = 1; $i <= self::SYNCHRONIZATION_BATCH_LENGTH; $i++) {
            if ($batchNumber === 1 && $i === 1) {
                $this->apiClientRepository->changeBatchUpdateTime();
            }
            if ($i % self::SYNCHRONIZATION_BATCH_LENGTH === 0) {
                if ($this->getTimeDifferenceInSeconds() < 30) {
                    $this->apiClientRepository->changeBatchUpdateTime();
                } else {
                    $this->changeSyncStatus(SyncStatus::ERROR);
                    break;
                }
            }
            if ($i === count($recipients) && $batchNumber === (int)ceil($numberOfCustomers / self::SYNCHRONIZATION_BATCH_LENGTH)) {
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