<?php

namespace CleverReachIntegration\DataAccessLayer;

use CleverReachIntegration\Presentation\Models\APIClient;

/**
 * Class APIClientRepository
 * @package CleverReachIntegration\DataAccessLayer
 */
class APIClientRepository
{
    /**
     * @param $token
     * @param $id
     * @return bool
     * @throws \PrestaShopException
     */
    public function createApiClient($token, $id)
    {
        $apiClient = new APIClient();
        $apiClient->accessToken = $token;
        $apiClient->idField = $id;
        return $apiClient->save();
    }

    /**
     * @return false|string
     */
    public function getClientID()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `id_field` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return \Db::getInstance()->getValue($query);
    }

    /**
     * @return string
     */
    private function getApiClientTable()
    {
        return 'api_client_table';
    }
}