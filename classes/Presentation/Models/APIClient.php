<?php

namespace CleverReachIntegration\Presentation\Models;

use ObjectModelCore;

class APIClient extends ObjectModelCore
{
    /**
     * @var int
     */
    public $id_client;
    /**
     * @var string
     */
    public $access_token;
    /**
     * @var string
     */
    public $id_field;
    /**
     * @var array
     */
    public static $definition = array(
        'table' => 'api_client_table',
        'primary' => 'id_client',
        'multilang' => false,
        'fields' => array(
            'access_token' => array('type' => self::TYPE_STRING, 'required' => true),
            'id_field' => array('type' => self::TYPE_STRING, 'required' => true)
        )
    );

}