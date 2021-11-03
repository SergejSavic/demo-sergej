<?php

use CleverReachIntegration\BusinessLogic\Services\APIClientService;
use CleverReachIntegration\BusinessLogic\Services\RecipientService;

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
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
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