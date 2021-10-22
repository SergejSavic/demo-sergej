<?php

use App\BusinessLogic\Services\AuthenticationService;
use App\BusinessLogic\Validators\AuthenticationValidator;
use App\BusinessLogic\Services\APIClientService;

class DemoViewModuleFrontController extends ModuleFrontController
{
    private AuthenticationService $authService;
    private APIClientService $apiClientService;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->authService = new AuthenticationService();
        $this->apiClientService = new APIClientService();
        parent::__construct();
    }

    public function initContent()
    {
        $accessParameters = $this->authService->getAccessParameters($_GET['code']);
        $id = $this->authService->getId($accessParameters['access_token']);

        if (AuthenticationValidator::validate($accessParameters)) {
            $this->apiClientService->createApiClient($accessParameters['access_token'], $id);
        }
    }

}