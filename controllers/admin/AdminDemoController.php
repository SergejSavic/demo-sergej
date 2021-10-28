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
        $this->apiClientService->synchronize();
        /*if ($this->apiClientService->isFirstTimeLoad()) {
            $this->apiClientService->changeLoadStatus();
            $this->apiClientService->synchronize();
        }*/
        $this->content .= $template->fetch();
        parent::initContent();
    }

    public function ajaxProcessCheck()
    {
        /*$order = new Order(7);
        $customer = new Customer(5);
        $shop = new ShopGroupCore();
        $price = $order->total_paid;
        $products = $order->getProducts();
        $customers = CustomerCore::getCustomers();
        $orders = Order::getCustomerOrders(5);*/
        //$products = $orders->getProducts();

        echo json_encode('something');//something you want to return
        exit;
    }

    public function ajaxProcessCheckIfClientExist()
    {
        $clientID = $this->apiClientService->returnApiClientID();
        echo ($clientID !== false) ? json_encode(true) : json_encode(false);
        exit;
    }

}