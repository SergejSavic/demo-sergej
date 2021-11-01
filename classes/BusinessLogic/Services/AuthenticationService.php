<?php

namespace CleverReachIntegration\BusinessLogic\Services;

use CleverReachIntegration\BusinessLogic\HTTP\Proxy;

/**
 * Class AuthenticationService
 * @package CleverReachIntegration\BusinessLogic\Services
 */
class AuthenticationService
{
    /**
     * @var Proxy
     */
    private $proxy;

    /**
     * Initializes proxy service
     */
    public function __construct()
    {
        $this->proxy = new Proxy();
    }

    /**
     * @return string
     */
    public static function getClientID()
    {
        return 'rbUPpLYzJh';
    }

    /**
     * @return string
     */
    public static function getClientSecret()
    {
        return 'mk2yKjaXbomIcYpf0xqlRrohADIWm2YS';
    }

    /**
     * @param string $code
     * @return array
     */
    public function getAccessParameters($code)
    {
        $fields = array('client_id' => self::getClientID(), 'client_secret' => self::getClientSecret(),
            'redirect_uri' => 'http://prestashop.test/en/module/demo/view', 'grant_type' => 'authorization_code', 'code' => $code);

        return $this->proxy->post("https://rest.cleverreach.com/oauth/token.php", $fields);
    }

    /**
     * @param string $accessToken
     * @return string
     */
    public function getId($accessToken)
    {
        $userData = $this->proxy->getWithHTTPHeader('https://rest.cleverreach.com/v3/debug/whoami.json', $accessToken);

        return $userData['id'];
    }
}