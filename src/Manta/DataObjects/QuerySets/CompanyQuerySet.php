<?php
//declare(strict_types=1);

namespace Manta\DataObjects\QuerySets;


use Manta\DataObjects\Objects\Company;
use Manta\DataObjects\Objects\Order;
use Manta\Rest\RestResponse;

class CompanyQuerySet extends QuerySet
{

    public function __construct ($apiClient, $resource, $token, $queryFilters) {
        parent::__construct ($apiClient, $resource, $token, $queryFilters);
    }

    protected function _generateDataObjects(RestResponse $response)
    {
        return array_map(function($data){return new Company($data);}, $response->body['companies']);
    }
}