<?php

use CleverReachIntegration\BusinessLogic\Services\APIClientService;
use Symfony\Component\HttpFoundation\JsonResponse;

class DemoValidationModuleFrontController extends ModuleFrontController
{
    private $apiClientService;

    public function __construct()
    {
        parent::__construct();
        $this->apiClientService = new APIClientService();
        $this->bootstrap = true;
        $this->ajax = true;
    }

    public function initContent()
    {
        $clientID = $this->apiClientService->returnApiClientID();
        if ($clientID !== false) {
            return new JsonResponse(200);
        } else {
            return new JsonResponse(400, ['message' => 'error']);
        }
    }

}