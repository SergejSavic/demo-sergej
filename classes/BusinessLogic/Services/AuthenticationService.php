<?php

namespace CleverReachIntegration\BusinessLogic\Services;

use CleverReachIntegration\BusinessLogic\HTTP\Proxy;

class AuthenticationService
{
    /**
     * @var Proxy
     */
    private $proxy;

    public function __construct()
    {
        $this->proxy = new Proxy();
    }

    /**
     * @return string
     */
    public static function getClientID(): string
    {
        return 'rbUPpLYzJh';
    }

    /**
     * @return string
     */
    public static function getClientSecret(): string
    {
        return 'mk2yKjaXbomIcYpf0xqlRrohADIWm2YS';
    }

    /**
     * @param string $code
     * @return array
     */
    public function getAccessParameters(string $code): array
    {
        $fields = ['client_id' => AuthenticationService::getClientID(), 'client_secret' => AuthenticationService::getClientSecret(),
            'redirect_uri' => 'http://prestashop.test/en/module/demo/view', 'grant_type' => 'authorization_code', 'code' => $code];

        return $this->proxy->post("https://rest.cleverreach.com/oauth/token.php", $fields);
    }

    /**
     * @param string $access_token
     * @return string
     */
    public function getId(string $access_token): string
    {
        $userData = $this->proxy->getWithHTTPHeader('https://rest.cleverreach.com/v3/debug/whoami.json', $access_token);

        return $userData['id'];
    }
}