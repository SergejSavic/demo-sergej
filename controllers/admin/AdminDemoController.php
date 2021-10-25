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
        if (!$this->apiClientService->returnApiClientID()) {
            $template = $this->context->smarty->createTemplate($this->getTemplatePath() . 'origin.tpl', $this->context->smarty);
        } else {
            $clientID = $this->apiClientService->returnApiClientID();
            $template = $this->context->smarty->createTemplate($this->getTemplatePath() . 'sync_page.tpl', $this->context->smarty);
            $template->assign(array(
                'clientID' => $clientID
            ));
        }

        $this->content .= $template->fetch();
        parent::initContent();
    }

}