<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_'))
    return false;

class Demo extends Module
{
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
        return parent::install();
    }
}


