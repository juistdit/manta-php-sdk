<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 09-02-17
 * Time: 12:40
 */

namespace Manta;


use Manta\Exceptions\NoSuchValueException;
use Manta\Http\Clients\HttpClientInterface;

class MantaSession
{

    private $_apiClient;

    private $_token;

    public function __construct(HttpClientInterface $apiClient, string $token){
        $this->_apiClient = $apiClient;
        $this->_token = $token;
    }

    public function getCompany(string $id){
        throw new NoSuchValueException();
    }

    public function getCompanies(){
        $companies = [];
        return new \IteratorIterator(new \ArrayIterator($companies));
    }
}