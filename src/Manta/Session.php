<?php
declare(strict_types=1);

namespace Manta;


use Manta\DataObjects\Objects\Order;
use Manta\DataObjects\QuerySets\CompanyQuerySet;
use Manta\DataObjects\QuerySets\OrderQuerySet;
use Manta\DataObjects\Objects\Company;
use Manta\Rest\RestClientInterface;

class Session
{

    private $_apiClient;

    private $_token;

    public function __construct(RestClientInterface $apiClient, string $token){
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
        return new CompanyQuerySet($api, $resource, $token, []);
    }

    public function getOrder($id) {
        $api = $this->_apiClient;
        $token = $this->_token;
        $resource = "brand/orders/$id";
        $response = $api->GET($resource, ['Authorization' => "Bearer $token"]);
        if($response->isError()){
            throw $response->asException();
        }
        return new Order($response->body);
    }

    public function getOrders() {
        $api = $this->_apiClient;
        $token = $this->_token;
        $resource = 'brand/orders';
        return new OrderQuerySet($api, $resource, $token, []);
    }
}