<?php

use CleverReachIntegration\BusinessLogic\Services\APIClientService;

/**
 * Class AdminDemoController
 */
class AdminDemoController extends ModuleAdminController
{
    /**
     * @var APIClientService
     */
    private $apiClientService;

    /**
     * @throws PrestaShopException
     */
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
        if (!$this->apiClientService->clientExists()) {
            $this->setTemplateFile('origin.tpl', array());
        } else {
            $clientID = $this->apiClientService->getClientID();
            $this->setTemplateFile('syncPage.tpl', array('clientID' => $clientID));
        }
        parent::initContent();
    }

    /**
     * @param string $templateName
     * @param array $variables
     * @throws SmartyException
     */
    public function setTemplateFile($templateName, $variables)
    {
        $template = $this->context->smarty->createTemplate($this->getTemplatePath() . $templateName, $this->context->smarty);
        $template->assign($variables);
        $this->content .= $template->fetch();
    }

}