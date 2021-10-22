<?php

class AdminDemoSynchronizationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        $this->setTemplate($this->module->template_dir . 'sync_page.tpl');
    }

}