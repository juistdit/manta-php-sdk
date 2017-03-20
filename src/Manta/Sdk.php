<?php
//declare(strict_types=1);

namespace Manta;

class Sdk {

    const VERSION = '0.0-dev';
    const API_URL = '';
    const HTTP_CLIENT = '\Manta\Rest\Json\Clients\CurlClient';

    private $_config;
    private $_apiClient;

    public function __construct(array $config = []) {
        $this->_config = array_merge([
            'api_url' => static::API_URL,
            'http_client' => static::HTTP_CLIENT
        ], $config);
        $clientClass = $this->_config['http_client'];
        $this->_apiClient = new $clientClass($this->_config['api_url']);
    }

    public function login($username, $password){
        //retrieve the token
        $api = $this->_apiClient;
        $response = $api->POST('integration/customer/token', ['username'=>$username, 'password'=>$password]);
        if($response->isError()){
            throw $response->asException();
        }
        $token = $response->body;
        return new Session($this->_apiClient, $token);
    }
}