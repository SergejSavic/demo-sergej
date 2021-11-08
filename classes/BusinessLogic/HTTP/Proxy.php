<?php

namespace CleverReachIntegration\BusinessLogic\HTTP;

/**
 * Class Proxy
 * @package CleverReachIntegration\BusinessLogic\HTTP
 */
class Proxy
{
    /**
     * @param string $url
     * @param array $fields
     * @return mixed
     */
    public function post($url, $fields)
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
     * @param string $accessToken
     * @return mixed
     */
    public function getWithHTTPHeader($url, $accessToken)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curl), true);
    }

    /**
     * @param string $url
     * @param string $fields
     * @param string $accessToken
     * @return mixed
     */
    public function postWithHTTPHeader($url, $fields, $accessToken)
    {
        $url .= '?token=' . $accessToken;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return json_decode(curl_exec($curl), true);
    }

    /**
     * @param $url
     * @param $data
     * @param $accessToken
     */
    public function put($url, $data, $accessToken)
    {
        $url .= '?token=' . $accessToken;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($curl);
    }

    /**
     * @param $url
     * @param $accessToken
     * @return bool|string
     */
    public function delete($url, $accessToken)
    {
        $url .= '?token=' . $accessToken;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

        return curl_exec($curl);
    }
}