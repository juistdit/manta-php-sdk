<?php
declare(strict_types=1);

namespace Manta;


use Manta\Exceptions\MantaApiException;
use Manta\DataObjects\Company;
use Manta\Exceptions\MantaApiExceptionFactory;
use Manta\Rest\Json\Clients\JsonClientInterface;

class Session
{

    private $_apiClient;

    private $_token;

    public function __construct(JsonClientInterface $apiClient, string $token){
        $this->_apiClient = $apiClient;
        $this->_token = $token;
    }

    public function getCompany(int $id){
        $api = $this->_apiClient;
        $token = $this->_token;
        //$id has been validated by php type-checking
        $resource = "brand/companies/$id";
        $response = $api->GET($resource, ['Authorization' => "Bearer $token"]);
        if($response->isError()) {
            throw $response->asException();
        }
        return new Company($response->body);
    }

    public function getCompanies(){
        $api = $this->_apiClient;
        $token = $this->_token;
        $resource = 'brand/companies';
        $response = $api->GET($resource, ['Authorization' => "Bearer $token"]);
        if($response->isError()) {
            throw $response->asException();
        }
        $companies = $response->body['companies'];
        $companies = array_map(function($c){return new Company($c);}, $companies);
        return new \IteratorIterator(new \ArrayIterator($companies));
    }
}