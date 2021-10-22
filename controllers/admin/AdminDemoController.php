<?php

use App\BusinessLogic\Services\APIClientService;

class AdminDemoController extends ModuleAdminController
{
    private APIClientService $apiClientService;
    public function __construct()
    {
        $this->bootstrap = true;
        $this->apiClientService = new APIClientService();
        parent::__construct();
    }

    public function initContent()
    {
        /*
        parent::initContent();
        $this->setTemplate($this->module->template_dir . 'origin.tpl');
        */
        //$apis = $this->apiClientService->getApiClients();

        $tpl = $this->context->smarty->createTemplate($this->getTemplatePath() . 'origin.tpl', $this->context->smarty);
        $tpl->assign(array(
            'my_var' => "test"
        ));
        $this->content .= $tpl->fetch();
        parent::initContent();
    }

}