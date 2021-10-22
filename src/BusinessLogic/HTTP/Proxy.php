<?php

namespace App\BusinessLogic\HTTP;

class Proxy
{
    public function get(string $url, array $fields)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, sizeof($fields));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curl), true);
    }
}