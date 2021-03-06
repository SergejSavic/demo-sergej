<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_'))
    return false;

/**
 * Class Demo
 */
class Demo extends Module
{
    /**
     * @var array[]
     */
    public $tabs = array(
        array(
            'name' => 'CleverReach Demo',
            'class_name' => 'AdminDemo',
            'visible' => true,
            'parent_class_name' => 'Marketing',
        ),
    );

    /**
     * Initializes plugin info
     */
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

    /**
     * @return bool
     */
    public function install()
    {
        return parent::install() &&
            Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'api_client_table` (
            `idClient` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `accessToken` varchar(500) NOT NULL,
            `idField` varchar(100) NOT NULL,
            PRIMARY KEY (`idClient`)
            ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;')
            && $this->registerHooksMethod();
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall() &&
            Db::getInstance()->execute('DROP TABLE IF EXISTS`' . _DB_PREFIX_ . 'api_client_table`');
    }

    /**
     * Calls method to set css and js to controller
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (false !== strpos(Tools::getValue('controller'), 'AdminDemo')) {
            $this->initControllerAssets();
        }
    }

    /**
     * Sets css and js files for admin controllers
     */
    private function initControllerAssets()
    {
        if (Tools::getValue('controller') === 'AdminDemo') {
            $adminAjaxLink = $this->context->link->getAdminLink('AdminDemo');
            $cleverReachURL = 'http://rest.cleverreach.com/oauth/authorize.php?client_id=rbUPpLYzJh&grant=basic&response_type=code&redirect_uri='.
                Tools::getHttpHost(true) . __PS_BASE_URI__ . 'en/module/demo/view';
            Media::addJsDef(array(
                "adminAjaxLink" => $adminAjaxLink,
                'cleverReachURL' => $cleverReachURL
            ));
            $this->context->controller->addCSS($this->_path . 'views/dist/css/admin.css');
            $this->context->controller->addCSS($this->_path . 'views/dist/css/syncPage.css');
            $this->context->controller->addJS($this->_path . 'views/dist/js/back.js');
        }
    }

    /**
     * @return bool
     */
    private function registerHooksMethod()
    {
        return $this->registerHook('displayBackOfficeHeader') && $this->registerHook('actionFrontControllerSetMedia');
    }

}


