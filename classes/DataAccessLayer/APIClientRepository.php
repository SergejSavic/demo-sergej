<?php

namespace CleverReachIntegration\DataAccessLayer;

use CleverReachIntegration\Presentation\Models\APIClient;
use Db;

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
        $apiClient->syncStatus = 'none';
        $apiClient->isFirstTimeLoad = 1;
        $apiClient->lastBatchUpdatedTime = date("Y-m-d H:i:s");

        return $apiClient->save();
    }

    /**
     * @return false|string
     */
    public function getClientID()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `idField` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return Db::getInstance()->getValue($query);
    }

    /**
     * @return false|string
     */
    public static function returnAccessToken()
    {
        $tableName = 'api_client_table';
        $query = 'SELECT `accessToken` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return Db::getInstance()->getValue($query);
    }

    /**
     * @return bool
     */
    public function isFirstTimeLoad()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `isFirstTimeLoad` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return (int)Db::getInstance()->getValue($query) === 1;
    }

    /**
     * Changes load status of the synchronization page
     */
    public function changeLoadStatus()
    {
        $id = $this->getClientID();
        $tableName = $this->getApiClientTable();
        $updateData = array('isFirstTimeLoad' => 0);
        Db::getInstance()->update($tableName, $updateData, 'idField=' . $id);
    }

    /**
     * @param int $id
     */
    public function changeBatchUpdateTime($id = 1)
    {
        $tableName = $this->getApiClientTable();
        $updateData = array('lastBatchUpdatedTime' => date("Y-m-d H:i:s"));
        \Db::getInstance()->update($tableName, $updateData, 'idClient=' . $id);
    }

    /**
     * @return false|string
     */
    public function getLastBatchUpdatedTime()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `lastBatchUpdatedTime` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return Db::getInstance()->getValue($query);
    }

    /**
     * @param string $status
     */
    public function changeSyncStatus($status)
    {
        $tableName = $this->getApiClientTable();
        $id = $this->getClientID();
        $updateData = array('syncStatus' => $status);
        \Db::getInstance()->update($tableName, $updateData, 'idField=' . $id);
    }

    /**
     * @return false|string
     */
    public function getSyncStatus()
    {
        $tableName = $this->getApiClientTable();
        $query = 'SELECT `syncStatus` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return Db::getInstance()->getValue($query);
    }

    /**
     * @return string
     */
    private function getApiClientTable()
    {
        return 'api_client_table';
    }
}