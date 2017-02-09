<?php

namespace Manta;

class Sdk {

    const VERSION = '0.0-dev';
    const API_URL = '';
    const HTTP_CLIENT = '\Manta\Http\Clients\CurlClient';

    private $_config;
    private $_apiClient;

    public function __construct(array $config) {
        $this->_config = array_merge([
            'api_url' => static::API_URL,
            'http_client' => static::HTTP_CLIENT
        ], $config);
        $clientClass = $config['http_client'];
        $this->_apiClient = new $clientClass($this->_config['api_url']);
    }

    public function login(string $username, string $password){
        //retrieve the token
        $token = "";
        return new Session($this->_apiClient, $token);
    }
}