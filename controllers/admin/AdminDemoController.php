<?php

use CleverReachIntegration\BusinessLogic\Services\APIClientService;

class AdminDemoController extends ModuleAdminController
{
    /**
     * @var APIClientService
     */
    private $apiClientService;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->apiClientService = new APIClientService();
        parent::__construct();
    }

    /**
     * @throws SmartyException
     */
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
        if ($this->apiClientService->isFirstTimeLoad()) {
            $this->apiClientService->changeLoadStatus();
            $this->apiClientService->synchronize();
        }
        $this->content .= $template->fetch();
        parent::initContent();
    }

    public function ajaxProcessCheckIfClientExist()
    {
        $clientID = $this->apiClientService->returnApiClientID();
        echo ($clientID !== false) ? json_encode(true) : json_encode(false);
        exit;
    }

}