<?php

use CleverReachIntegration\BusinessLogic\Services\APIClientService;

/**
 * Class AdminDemoController
 */
class AdminDemoController extends ModuleAdminController
{
    /**
     * @var string
     */
    const BASE_IMG_URL = 'modules/cleverreach/views/img/';
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
        $url = Tools::getHttpHost(true) . __PS_BASE_URI__ . self::BASE_IMG_URL;
        if (!$this->apiClientService->clientExists()) {
            $this->setTemplateFile('origin.tpl', array('headerImage' => $url . 'logo_cleverreach.svg', 'contentImage' => $url . 'icon_hello.png'));
        } else {
            $clientID = $this->apiClientService->getClientID();
            $this->setTemplateFile('syncPage.tpl', array('clientID' => $clientID, 'headerImage' => $url . 'logo_cleverreach.svg'));
        }
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
        parent::initContent();
    }

}