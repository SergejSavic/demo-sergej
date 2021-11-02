<?php

namespace CleverReachIntegration\BusinessLogic\Services;

use CleverReachIntegration\DataAccessLayer\APIClientRepository;
use PrestaShopException;

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
     * Initializes client repository
     */
    public function __construct()
    {
        $this->apiClientRepository = new APIClientRepository();
    }

    /**
     * @return bool
     */
    public function clientExists()
    {
        return (bool)$this->getClientID();
    }

    /**
     * @param string $token
     * @param $id
     * @return bool
     * @throws PrestaShopException
     */
    public function createApiClient($token, $id)
    {
        return $this->apiClientRepository->createApiClient($token, $id);
    }

    /**
     * @return false|string
     */
    public function getClientID()
    {
        return $this->apiClientRepository->getClientID();
    }

    /**
     * @return bool
     */
    public function isFirstTimeLoad()
    {
        return $this->apiClientRepository->isFirstTimeLoad();
    }

    /**
     * @return false|string
     */
    public function getSyncStatus()
    {
        return $this->apiClientRepository->getSyncStatus();
    }

    /**
     * Changes load status of the synchronization page
     */
    public function changeLoadStatus()
    {
        $this->apiClientRepository->changeLoadStatus();
    }

}