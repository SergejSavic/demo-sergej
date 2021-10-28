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
}