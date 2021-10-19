<?php

class AdminDemoController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        $this->setTemplate($this->module->template_dir . 'origin.tpl');
    }
}