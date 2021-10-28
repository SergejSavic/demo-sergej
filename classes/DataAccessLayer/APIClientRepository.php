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
        $apiClient->syncStatus = 'none';
        $apiClient->isFirstTimeLoad = 1;
        $apiClient->save();
    }

    /**
     * @return false|string
     */
    public function returnApiClientID()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `idField` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return \Db::getInstance()->getValue($query);
    }

    /**
     * @return false|string
     */
    public static function returnAccessToken()
    {
        $tableName = 'api_client_table';
        $query = 'SELECT `accessToken` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return \Db::getInstance()->getValue($query);
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getCurrencyById($id)
    {
        $tableName = 'currency';
        $query = 'SELECT `iso_code` FROM `' . _DB_PREFIX_ . pSQL($tableName) .
            '` WHERE `id_currency` = "'.pSQL($id).'"';

        return \Db::getInstance()->getValue($query);
    }

    /**
     * @return bool
     */
    public function isFirstTimeLoad()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `isFirstTimeLoad` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return \Db::getInstance()->getValue($query) == 1;
    }

    /**
     * Changes load status of the synchronization page
     */
    public function changeLoadStatus()
    {
        $id = $this->returnApiClientID();
        $tableName = $this->getApiClientTable();
        $updateData = array('isFirstTimeLoad' => 0);
        \Db::getInstance()->update($tableName, $updateData, 'idField=' .$id);
    }

    /**
     * @param string $status
     */
    public function changeSyncStatus($status)
    {
        $id = $this->returnApiClientID();
        $tableName = $this->getApiClientTable();
        $updateData = array('syncStatus' => $status);
        \Db::getInstance()->update($tableName, $updateData, 'idField=' .$id);
    }

    /**
     * @return string
     */
    private function getApiClientTable()
    {
        return 'api_client_table';
    }
}