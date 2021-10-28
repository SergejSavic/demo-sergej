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
     * @param string $token
     * @param string $id
     * @throws \PrestaShopException
     */
    public function createApiClient($token, $id)
    {
        $apiClient = new APIClient();
        $apiClient->accessToken = $token;
        $apiClient->idField = $id;
        $apiClient->save();
    }

    /**
     * @return false|string
     */
    public function returnApiClientID()
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