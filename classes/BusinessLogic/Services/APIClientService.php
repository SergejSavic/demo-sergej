<?php

namespace CleverReachIntegration\BusinessLogic\Services;

use CleverReachIntegration\DataAccessLayer\APIClientRepository;

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
     * Initializes api client repository
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
     * @param string $id
     * @return bool
     * @throws \PrestaShopException
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
}