<?php

namespace CleverReachIntegration\BusinessLogic\Services;

use CleverReachIntegration\DataAccessLayer\APIClientRepository;
use CleverReachIntegration\BusinessLogic\HTTP\Proxy;
use CleverReachIntegration\Presentation\Models\Group;

/**
 * Class APIClientService
 * @package CleverReachIntegration\BusinessLogic\Services
 */
class APIClientService
{
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
     * Initializes client repository and access token
     */
    public function __construct()
    {
        $this->proxy = new Proxy();
        $this->apiClientRepository = new APIClientRepository();
        $this->token = APIClientRepository::returnAccessToken();
    }

    /**
     * @param string $token
     * @param string $id
     */
    public function createApiClient($token, $id)
    {
        return $this->apiClientRepository->createApiClient($token, $id);
    }

    /**
     * @return false|string
     */
    public function returnApiClientID()
    {
        return $this->apiClientRepository->returnApiClientID();
    }

    /**
     * Synchronizes recipients on api
     */
    public function synchronize()
    {
        $this->changeSyncStatus("in progress");
        $group = $this->getApiGroup();
        //$this->getPrestaShopCustomers();
    }

    /**
     * @return bool
     */
    public function isFirstTimeLoad()
    {
        return $this->apiClientRepository->isFirstTimeLoad();
    }

    /**
     * Changes load status of the synchronization page
     */
    public function changeLoadStatus()
    {
        $this->apiClientRepository->changeLoadStatus();
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
     * Returns prestashop customers
     */
    private function getPrestaShopCustomers()
    {
        //return all active customers
    }
}