<?php

namespace CleverReachIntegration\DataAccessLayer;

use CleverReachIntegration\Presentation\Models\APIClient;

class APIClientRepository
{
    /**
     * @param string $token
     * @param string $id
     * @throws \PrestaShopException
     */
    public function createApiClient(string $token, string $id)
    {
        $apiClient = new APIClient();
        $apiClient->access_token = $token;
        $apiClient->id_field = $id;
        $apiClient->sync_status = 'none';
        $apiClient->is_first_time_load = 1;
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
        $query = 'SELECT `is_first_time_load` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return (\Db::getInstance()->getValue($query) == 1 ) ? true : false;
    }

    public function changeLoadStatus()
    {
        $id = $this->returnApiClientID();
        $tableName = $this->getApiClientTable();
        $updateData = array('is_first_time_load' => 0);
        \Db::getInstance()->update($tableName, $updateData, 'id_field=' .$id);
    }

    /**
     * @param string $status
     */
    public function changeSyncStatus($status)
    {
        $id = $this->returnApiClientID();
        $tableName = $this->getApiClientTable();
        $updateData = array('sync_status' => $status);
        \Db::getInstance()->update($tableName, $updateData, 'id_field=' .$id);
    }

    /**
     * @return string
     */
    private function getApiClientTable()
    {
        return 'api_client_table';
    }
}