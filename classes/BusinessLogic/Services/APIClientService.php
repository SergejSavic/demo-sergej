<?php

namespace CleverReachIntegration\BusinessLogic\Services;

use CleverReachIntegration\DataAccessLayer\APIClientRepository;

class APIClientService
{
    /**
     * @var APIClientRepository
     */
    private $apiClientRepository;

    public function __construct()
    {
        $this->apiClientRepository = new APIClientRepository();
    }

    /**
     * @param string $token
     * @param string $id
     */
    public function createApiClient(string $token, string $id)
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

    public function synchronize()
    {
        $this->changeSyncStatus("in progress");
        $this->getApiGroup();
        $this->getApiCustomers();
    }

    /**
     * @return bool
     */
    public function isFirstTimeLoad()
    {
        return $this->apiClientRepository->isFirstTimeLoad();
    }

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

    private function getApiGroup()
    {
        if(isGroupExisting("name")) {
            //return group
        } else {
            //create new group
        }
    }

    private function isGroupExisting(string $name)
    {
        //return group if exists
    }

    private function getApiCustomers()
    {
        //return all active customers
    }
}