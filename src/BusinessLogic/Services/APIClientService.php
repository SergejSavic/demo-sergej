<?php

namespace App\BusinessLogic\Services;

use App\DataAccessLayer\APIClientRepository;

class APIClientService
{
    private APIClientRepository $apiClientRepository;

    public function __construct()
    {
        $this->apiClientRepository = new APIClientRepository();
    }

    public function createApiClient(string $token)
    {
        return $this->apiClientRepository->createApiClient($token);
    }

    public function getApiClients()
    {
        return $this->apiClientRepository->getApiClients();
    }
}