<?php

use CleverReachIntegration\BusinessLogic\Services\APIClientService;
use CleverReachIntegration\BusinessLogic\Services\RecipientService;

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
     * @var RecipientService
     */
    private $recipientService;

    /**
     * @throws PrestaShopException
     */
    public function __construct()
    {
        $this->bootstrap = true;
        $this->apiClientService = new APIClientService();
        $this->recipientService = new RecipientService();
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
     * Checks if api client exist
     */
    public function ajaxProcessCheckIfClientExist()
    {
        $clientID = $this->apiClientService->getClientID();
        echo ($clientID !== false) ? json_encode(true) : json_encode(false);
        exit;
    }

    /**
     * Checks if sync page is loaded for the first time
     */
    public function ajaxProcessIsFirstTimeLoad()
    {
        $loadStatus = $this->apiClientService->isFirstTimeLoad();
        echo json_encode($loadStatus);
        exit;
    }

    /**
     * Changes load status
     */
    public function ajaxProcessChangeLoadStatus()
    {
        $this->apiClientService->changeLoadStatus();
    }

    /**
     * Checks sync status
     */
    public function ajaxProcessCheckSyncStatus()
    {
        $syncStatus = $this->apiClientService->getSyncStatus();
        echo json_encode($syncStatus);
        exit;
    }

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function ajaxProcessSynchronize()
    {
        $this->recipientService->synchronize();
    }

    /**
     * @param string $templateName
     * @param array $variables
     * @throws SmartyException
     */
    private function setTemplateFile($templateName, $variables)
    {
        $template = $this->context->smarty->createTemplate($this->getTemplatePath() . $templateName, $this->context->smarty);
        $template->assign($variables);
        $this->content .= $template->fetch();
        parent::initContent();
    }

}