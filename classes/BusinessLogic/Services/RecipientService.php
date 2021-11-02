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
        $this->changeSyncStatus("In progress");
        $group = $this->getApiGroup();
        $recipients = $this->prepareRecipients();
        $recipientsCount = count($recipients);
        $this->synchronizeInBatches($recipients, $recipientsCount, $group);
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
        $groupModel = new Group('prestashopCustomers', 'Prestashop customers');
        $fields = json_encode($groupModel->getArray());

        return ($this->isGroupExisting('prestashopCustomers')) ?:
            $this->proxy->postWithHTTPHeader('https://rest.cleverreach.com/v3/groups.json', $fields, $this->token);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    private function isGroupExisting($name)
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
     * @return mixed
     * @throws PrestaShopDatabaseException
     */
    private function getPrestaShopCustomers()
    {
        return $this->recipientRepository->getPrestaShopCustomers();
    }

    /**
     * @return array
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function prepareRecipients()
    {
        $customers = $this->getPrestaShopCustomers();
        $recipients = array();

        foreach ($customers as $customer) {
            $orders = $this->recipientRepository->getCustomerOrders($customer['id_customer']);
            $recipient = $this->createRecipient($orders, $customer);
            $recipients[] = $recipient;
        }

        return $recipients;
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
        $globalAttributes = RecipientValidator::validateRecipientGlobalAttributes($customer, $orders);
        $tags = $this->setRecipientTags($customer['id_customer']);

        return new Recipient($customer['email'], (explode(" ", $customer['date_add']))[0], (explode(" ", $customer['date_add']))[0],
            $customer['name'], array(), $globalAttributes, $tags, $recipientOrders);
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
     * @param $recipientsCount
     * @param $group
     * @throws Exception
     */
    private function synchronizeInBatches($recipients, $recipientsCount, $group)
    {
        for ($i = 1; $i <= $recipientsCount; $i++) {
            if ($i === 1) {
                $this->apiClientRepository->changeBatchUpdateTime();
            }
            if ($i % self::SYNCHRONIZATION_BATCH === 0) {
                if ($this->getTimeDifferenceInSeconds() < 30) {
                    $this->apiClientRepository->changeBatchUpdateTime();
                } else {
                    $this->changeSyncStatus("Error");
                    break;
                }
            }
            $recipientJSON = json_encode($recipients[$i - 1]->getArray());
            $this->proxy->postWithHTTPHeader("https://rest.cleverreach.com/v3/groups.json/" . $group['id'] . "/receivers", $recipientJSON, $this->token);

            if ($i === $recipientsCount) {
                $this->changeSyncStatus("Done");
            }
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