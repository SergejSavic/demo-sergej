<?php

namespace CleverReachIntegration\BusinessLogic\HTTP;

class Proxy
{
    /**
     * @param string $url
     * @param array $fields
     * @return mixed
     */
    public function post(string $url, array $fields)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, count($fields));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curl), true);
    }

    /**
     * @param string $url
     * @param string $access_token
     * @return mixed
     */
    public function getWithHTTPHeader(string $url, string $access_token)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curl), true);
    }

    /**
     * @param string $url
     * @param $fields
     * @param string $access_token
     * @return mixed
     */
    public function postWithHTTPHeader(string $url, $fields, string $access_token)
    {
        $url = $url . '?token=' . $access_token;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curl), true);
    }
}