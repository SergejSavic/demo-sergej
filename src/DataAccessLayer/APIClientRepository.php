<?php

namespace App\DataAccessLayer;

use App\Presentation\Models\APIClient;
use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopCollection;

class APIClientRepository
{
    public function createApiClient(string $token)
    {
        $apiClient = new APIClient();
        $apiClient->access_token = $token;
        $apiClient->save();
    }

    public function getApiClients()
    {
        $clients = new PrestaShopCollection('apiclient');
        $name = "sergej";
    }
}