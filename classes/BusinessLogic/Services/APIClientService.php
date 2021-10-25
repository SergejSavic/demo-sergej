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
}