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
     * @var bool
     */
    public $is_first_time_load;
    /**
     * @var string
     */
    public $sync_status;

    public static $definition = array(
        'table' => 'api_client_table',
        'primary' => 'id_client',
        'multilang' => false,
        'fields' => array(
            'access_token' => array('type' => self::TYPE_STRING, 'required' => true),
            'id_field' => array('type' => self::TYPE_STRING, 'required' => true),
            'is_first_time_load' => array('type' => self::TYPE_BOOL, 'required' => true),
            'sync_status' => array('type' => self::TYPE_STRING, 'required' => true)
        )
    );

}