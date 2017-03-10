<?php
declare(strict_types=1);

namespace Manta\Rest\Json\Clients;

use Manta\Rest\Json\JsonResponse;
use Manta\Exceptions\RestException;

class CurlClient extends AbstractClient
{

    private $_curl;
    private $_default_curl_config;
    private $_api_url;

    public function __construct(string $api_url) {
        //save the curl handle to enable pipelining.
        $this->_api_url = $api_url;
        $this->_curl = curl_init();
        $this->_default_curl_config = [
            CURLOPT_RETURNTRANSFER => true,
        ];
    }

    public function sendRequest(string $method, string $url, array $headers = [], array $data = null){
        $curl = $this->_curl;
        $config = $this->_default_curl_config;
        //make it an non associative array
        $headers = array_map(function($key, $value) { return "$key: $value";},
                        array_keys($headers), array_values($headers));
        if ($data !== null) {
            $headers[] = 'Content-Type: application/json';
            $config[CURLOPT_POSTFIELDS] = json_encode($data);
        }
        $url = $this->_api_url . $url;
        //we need to use the + operator: CURLOPT constants are numerical, array_merge renumbers them
        $config = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => true
        ] + $config;
        curl_setopt_array($curl, $config);

        $content = curl_exec ($curl);
        if($content === false) {
            throw new RestException(curl_error($curl), curl_errno($curl));
        }
        return new JsonResponse(['status' => curl_getinfo($curl, CURLINFO_HTTP_CODE), 'raw_body' => $content]);
    }
}