<?php

namespace App\BusinessLogic\Services;

use App\DataAccessLayer\APIClientRepository;
use App\Presentation\Models\APIClient;

class APIClientService
{
    private APIClientRepository $apiClientRepository;

    public function __construct()
    {
        $this->apiClientRepository = new APIClientRepository();
    }

    public function createApiClient(string $token, string $id)
    {
        return $this->apiClientRepository->createApiClient($token, $id);
    }

    public function returnApiClientID()
    {
        return $this->apiClientRepository->returnApiClientID();
    }
}