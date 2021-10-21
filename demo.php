<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_'))
    return false;

class Demo extends Module
{

    const HOOK_LIST = [
        'displayBackOfficeHeader'
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
        parent::install();
        $this->registerHook(static::HOOK_LIST);
        return true;
    }

    public function loadAsset()
    {
        $this->addJsDefList();
        $this->context->controller->addCSS($this->_path . 'views/dist/front.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/dist/back.js');
    }

    public function getContent()
    {
        $this->loadAsset();
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
            $this->context->controller->addCSS($this->_path . 'views/dist/admin.css');
            $this->context->controller->addJS($this->_path . 'views/dist/back.js');
        }
    }

}


