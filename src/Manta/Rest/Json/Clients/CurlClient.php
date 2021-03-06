<?php
//declare(strict_types=1);

namespace Manta\Rest\Json\Clients;

use Manta\Rest\Json\JsonResponse;
use Manta\Exceptions\RestException;

class CurlClient extends AbstractClient
{

    private $_curl;
    private $_default_curl_config;
    private $_api_url;
    private $_debug = false;


    public function __construct($api_url, $debug=false) {
        //save the curl handle to enable pipelining.
        $this->_api_url = $api_url;
        $this->_debug = $debug;
        $this->_curl = curl_init();
        $this->_default_curl_config = [
            CURLOPT_RETURNTRANSFER => true,
        ];

    }

    public function sendRequest($method, $url, array $headers = [], array $data = null){

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


        if ( $this->_debug) {
            echo PHP_EOL . '############# BEGIN DEBUG SENDING REQUEST #####################' . PHP_EOL . PHP_EOL ;
            echo 'REQUEST METHOD:  ' .  $method . PHP_EOL ;
            echo 'API URL:  ' .  $url . PHP_EOL ;
            echo 'REQUEST HEADERS:  ' .   var_export($headers, true) . PHP_EOL ;
            echo 'REQUEST DATA:  ' .   var_export($data,true) . PHP_EOL ;
            echo PHP_EOL . '############# END DEBUG SENDING REQUEST #####################' . PHP_EOL ;
        }

        $content = curl_exec ($curl);

        if ( $this->_debug) {
            file_put_contents('/tmp/content-'. microtime(true), PHP_EOL . 'REAL RESPONSE: ' .  $content . PHP_EOL);
        }


        if($content === false) {
            throw new RestException(curl_error($curl), curl_errno($curl));
        }

        $responseTemp = new JsonResponse(['status' => curl_getinfo($curl, CURLINFO_HTTP_CODE), 'raw_body' => $content]);

        return $responseTemp;
    }
}