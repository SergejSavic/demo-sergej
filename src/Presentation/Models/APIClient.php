<?php

namespace App\Presentation\Models;

use ObjectModelCore;

class APIClient extends ObjectModelCore
{
    public int $id_client;
    public string $access_token;

    public static $definition = array(
        'table' => 'api_client_table',
        'primary' => 'id_client',
        'multilang' => false,
        'fields' => array(
            'access_token' => array('type' => self::TYPE_STRING, 'required' => true)
        )
    );

}