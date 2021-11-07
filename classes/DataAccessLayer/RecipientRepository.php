<?php

namespace CleverReachIntegration\DataAccessLayer;

use PrestaShop\PrestaShop\Adapter\Entity\Context;
use PrestaShop\PrestaShop\Adapter\Entity\OrderState;
use PrestaShop\PrestaShop\Adapter\Entity\Shop;

/**
 * Class APIClientRepository
 * @package CleverReachIntegration\DataAccessLayer
 */
class RecipientRepository
{
    /**
     * @param $offset
     * @param $limit
     * @return array|bool|\mysqli_result|\PDOStatement|resource|null
     * @throws \PrestaShopDatabaseException
     */
    public function getPrestaShopCustomers($offset, $limit)
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            '
            SELECT p.`id_customer`, p.`email`, p.`firstname`, p.`lastname`, p.`date_add`, p.`id_shop`, p.`company`, p.`birthday`, p.`newsletter`,s.`name` as `shop`,
                   ad.`address1`,ad.`city`,ad.`phone`,ad.`postcode`,cnt.`name` as `country`
            FROM `' . _DB_PREFIX_ . 'customer` p LEFT JOIN `' . _DB_PREFIX_ . 'shop` s ON (p.`id_shop` = s.`id_shop`)
            LEFT JOIN `' . _DB_PREFIX_ . 'address` ad ON (ad.`id_customer` = p.`id_customer`)
            LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` cnt ON (ad.`id_country` = cnt.`id_country`)
            WHERE p.`active`=1 AND (cnt.`id_lang` is null or cnt.`id_lang`=1)
            GROUP BY p.`email`
            ORDER BY p.`id_customer` ASC
            limit ' . pSQL($limit) . ' OFFSET ' . pSQL($offset) . '
            '
        );
    }

    /**
     * @param $customerId
     * @return array|bool|\mysqli_result|\PDOStatement|resource|null
     * @throws \PrestaShopDatabaseException
     */
    public function getSingleCustomer($customerId)
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            '
            SELECT p.`id_customer`, p.`email`, p.`firstname`, p.`lastname`, p.`date_add`, p.`id_shop`, p.`company`, p.`birthday`, p.`newsletter`,s.`name` as `shop`
            ,ad.`address1`, ad.`city`,ad.`phone`,ad.`postcode`,ad.`id_address`,  cnt.`name` as `country`
            FROM `' . _DB_PREFIX_ . 'customer` p LEFT JOIN `' . _DB_PREFIX_ . 'shop` s ON (p.`id_shop` = s.`id_shop`)
            LEFT JOIN `' . _DB_PREFIX_ . 'address` ad ON (ad.`id_customer` = p.`id_customer`)
            LEFT JOIN `' . _DB_PREFIX_ . 'country_lang` cnt ON (ad.`id_country` = cnt.`id_country`)
            WHERE p.`active`=1 AND p.`id_customer`="' . pSQL($customerId) . '"
           '
        );
    }

    /**
     * @return mixed
     */
    public function getShopName()
    {
        $tableName = 'shop';
        $query = 'SELECT `name` FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`';

        return \Db::getInstance()->getValue($query);
    }

    /**
     * @return false|string
     */
    public function getCustomerNumber()
    {
        $tableName = 'customer';
        $query = 'SELECT count(*) FROM `' . _DB_PREFIX_ . pSQL($tableName) . '`
        WHERE `active`=1';

        return \Db::getInstance()->getValue($query);
    }

    /**
     * @param $id
     * @return array|bool|\mysqli_result|\PDOStatement|resource|null
     * @throws \PrestaShopDatabaseException
     */
    public function getAddressByCustomerId($id)
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            '
            SELECT `address1`, `city`, `phone`, `postcode`
            FROM `' . _DB_PREFIX_ . 'address`
            WHERE `id_customer` = "' . pSQL($id) . '" AND `deleted`=0 AND `active`=1'
        );
    }

    /**
     * @param $customerId
     * @return array|bool|\mysqli_result|\PDOStatement|resource|null
     * @throws \PrestaShopDatabaseException
     */
    public function getCustomerGroups($customerId)
    {
        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            '
            SELECT `id_group`
            FROM `' . _DB_PREFIX_ . 'customer_group`
            WHERE `id_customer` = "' . pSQL($customerId) . '"'
        );
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getGroupById($id)
    {
        $tableName = 'group_lang';
        $query = 'SELECT `name` FROM `' . _DB_PREFIX_ . pSQL($tableName) .
            '` WHERE `id_group` = "' . pSQL($id) . '" AND `id_lang` = 1';

        return \Db::getInstance()->getValue($query);
    }

    /**
     * @param $id_customer
     * @param false $show_hidden_status
     * @param Context|null $context
     * @return array|bool|\mysqli_result|\PDOStatement|resource
     * @throws \PrestaShopDatabaseException
     */
    public function getCustomerOrders($id_customer, $show_hidden_status = false, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        $orderStates = OrderState::getOrderStates((int)$context->language->id, false);
        $indexedOrderStates = array();
        foreach ($orderStates as $orderState) {
            $indexedOrderStates[$orderState['id_order_state']] = $orderState;
        }

        $res = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
        SELECT o.*,cu.`iso_code`,
          (SELECT SUM(od.`product_quantity`) FROM `' . _DB_PREFIX_ . 'order_detail` od WHERE od.`id_order` = o.`id_order`) nb_products,
          (SELECT oh.`id_order_state` FROM `' . _DB_PREFIX_ . 'order_history` oh
           LEFT JOIN `' . _DB_PREFIX_ . 'order_state` os ON (os.`id_order_state` = oh.`id_order_state`)
           WHERE oh.`id_order` = o.`id_order` ' .
            (!$show_hidden_status ? ' AND os.`hidden` != 1' : '') .
            ' ORDER BY oh.`date_add` DESC, oh.`id_order_history` DESC LIMIT 1) id_order_state
            FROM `' . _DB_PREFIX_ . 'orders` o
            LEFT JOIN `' . _DB_PREFIX_ . 'currency` cu ON (cu.`id_currency` = o.`id_currency`)
            WHERE o.`id_customer` = ' . (int)$id_customer .
            Shop::addSqlRestriction(Shop::SHARE_ORDER) . '
            GROUP BY o.`id_order`
            ORDER BY o.`date_add` DESC');

        if (!$res) {
            return array();
        }

        foreach ($res as $key => $val) {
            $orderState = !empty($val['id_order_state']) ? $indexedOrderStates[$val['id_order_state']] : null;
            $res[$key]['order_state'] = $orderState['name'] ?: null;
            $res[$key]['invoice'] = $orderState['invoice'] ?: null;
            $res[$key]['order_state_color'] = $orderState['color'] ?: null;
        }

        return $res;
    }

    /**
     * @return string
     */
    private function getApiClientTable()
    {
        return 'api_client_table';
    }
}