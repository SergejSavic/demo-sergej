<?php

use CleverReachIntegration\BusinessLogic\Services\APIClientService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class DemoValidationModuleFrontController
 */
class DemoValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @var APIClientService
     */
    private $apiClientService;

    /**
     * Initializes api client service
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiClientService = new APIClientService();
        $this->bootstrap = true;
        $this->ajax = true;
    }

    /**
     * @return JsonResponse
     */
    public function initContent()
    {
        $clientID = $this->apiClientService->getClientID();
        if ($clientID !== false) {
            return new JsonResponse(200);
        } else {
            return new JsonResponse(400, array("message" => "error"));
        }
    }

}