<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_'))
    return false;

class Demo extends Module
{
    const HOOK_LIST = [
        'displayBackOfficeHeader',
        'actionFrontControllerSetMedia'
    ];
    public $tabs = [
        [
            'name' => 'CleverReach Demo',
            'class_name' => 'AdminDemo',
            'visible' => true,
            'parent_class_name' => 'Marketing',
        ],
    ];

    public function __construct()
    {
        $this->name = 'demo';
        $this->author = 'Sergej Savic';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('My module', array(), 'Modules.Demo.Admin');
        $this->description = $this->trans('Allow store users to manipulate CleverReach customers.', array(), 'Modules.Demo.Admin');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'api_client_table` (
            `id_client` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `access_token` varchar(500) NOT NULL,
            `id_field` varchar(100) NOT NULL,
            `is_first_time_load` tinyint(1) NOT NULL,
            `sync_status` varchar(50) NOT NULL,
            PRIMARY KEY (`id_client`)
            ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;')
            && $this->registerHook(static::HOOK_LIST);
    }

    public function uninstall()
    {
        return parent::uninstall() &&
            Db::getInstance()->execute('DROP TABLE IF EXISTS`' . _DB_PREFIX_ . 'api_client_table`');
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (false !== strpos(Tools::getValue('controller'), 'AdminDemo')) {
            $this->initControllerAssets();
        }
    }

    private function initControllerAssets()
    {
        if (Tools::getValue('controller') === 'AdminDemo') {
            $adminAjaxLink = $this->context->link->getAdminLink('AdminDemo');
            Media::addJsDef(array(
                "adminAjaxLink" => $adminAjaxLink
            ));
            $this->context->controller->addCSS($this->_path . 'views/dist/css/admin.css');
            $this->context->controller->addCSS($this->_path . 'views/dist/css/sync_page.css');
            $this->context->controller->addJS($this->_path . 'views/dist/js/back.js');
        }
    }

}


