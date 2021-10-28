<?php

use CleverReachIntegration\BusinessLogic\Services\AuthenticationService;
use CleverReachIntegration\BusinessLogic\Validators\AuthenticationValidator;
use CleverReachIntegration\BusinessLogic\Services\APIClientService;

/**
 * Class DemoViewModuleFrontController
 */
class DemoViewModuleFrontController extends ModuleFrontController
{
    /**
     * @var AuthenticationService
     */
    private $authService;
    /**
     * @var APIClientService
     */
    private $apiClientService;

    /**
     * Initializes authentication and api client services
     */
    public function __construct()
    {
        $this->bootstrap = true;
        $this->authService = new AuthenticationService();
        $this->apiClientService = new APIClientService();
        parent::__construct();
    }

    /**
     * Creates api client
     */
    public function initContent()
    {
        $accessParameters = $this->authService->getAccessParameters($_GET['code']);
        $id = $this->authService->getId($accessParameters['access_token']);

        if (AuthenticationValidator::validate($accessParameters)) {
            $this->apiClientService->createApiClient($accessParameters['access_token'], $id);
        }
    }

}