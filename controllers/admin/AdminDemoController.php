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
        if (!$this->apiClientService->returnApiClient()) {
            $template = $this->context->smarty->createTemplate($this->getTemplatePath() . 'origin.tpl', $this->context->smarty);
        } else {
            $template = $this->context->smarty->createTemplate($this->getTemplatePath() . 'sync_page.tpl', $this->context->smarty);
            $template->assign(array(
                'my_var' => "test"
            ));
        }

        $this->content .= $template->fetch();
        parent::initContent();
    }

}