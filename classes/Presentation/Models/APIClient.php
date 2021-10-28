<?php

namespace CleverReachIntegration\Presentation\Models;

use ObjectModelCore;

/**
 * Class APIClient
 * @package CleverReachIntegration\Presentation\Models
 */
class APIClient extends ObjectModelCore
{
    /**
     * @var int
     */
    public $idClient;
    /**
     * @var string
     */
    public $accessToken;
    /**
     * @var string
     */
    public $idField;
    /**
     * @var bool
     */
    public $isFirstTimeLoad;
    /**
     * @var string
     */
    public $syncStatus;

    /**
     * @var array
     */
    public static $definition = array(
        'table' => 'api_client_table',
        'primary' => 'idClient',
        'multilang' => false,
        'fields' => array(
            'accessToken' => array('type' => self::TYPE_STRING, 'required' => true),
            'idField' => array('type' => self::TYPE_STRING, 'required' => true),
            'isFirstTimeLoad' => array('type' => self::TYPE_BOOL, 'required' => true),
            'syncStatus' => array('type' => self::TYPE_STRING, 'required' => true)
        )
    );

}