<?php

namespace App\DataAccessLayer;

use App\Presentation\Models\APIClient;

class APIClientRepository
{
    public function createApiClient(string $token, string $id)
    {
        $apiClient = new APIClient();
        $apiClient->access_token = $token;
        $apiClient->id_field = $id;
        $apiClient->save();
    }

    public function returnApiClientID()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `id_field` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';
        $res = \Db::getInstance()->getValue($query);
        return \Db::getInstance()->getValue($query);
    }

    private function getApiClientTable()
    {
        return 'api_client_table';
    }
}