<?php
declare(strict_types=1);

namespace Manta\DataObjects\QuerySets;

use Manta\Rest\RestResponse;

abstract class QuerySet implements \Iterator {

    protected $_apiClient;
    protected $_resource;
    protected $_token;
    protected $_queryFilters;

    private $_generator;

    public function __construct($apiClient, $resource, $token, array $queryFilters = []){
        $this->_apiClient = $apiClient;
        $this->_queryFilters = $queryFilters;
        $this->_resource = $resource;
        $this->_token = $token;
        $this->_generator = null;
    }

    private function _ensureGenerator() {
        if($this->_generator === null) {
            $this->_generator = $this->_generator();
        }
    }

    //returns a generator
    private function _generator(array $arrayQueryFilters = null, array $stringQueryFilters = null){
        if($arrayQueryFilters === null) {
            $arrayQueryFilters = array_filter('is_array', $this->_queryFilters);
            $stringQueryFilters = array_filter('is_string', $this->_queryFilters);
            return $this->_generator($arrayQueryFilters, $stringQueryFilters);
        }
        if(count($arrayQueryFilters) > 0) {
            $allFilter = end($arrayQueryFilters);
            $key = key($arrayQueryFilters);
            array_pop($arrayQueryFilters);
            foreach($allFilter as $value){
                $dataObjects = $this->_generator($arrayQueryFilters, array_merge($stringQueryFilters, [$key => $value]));
                //or yield from:
                foreach($dataObjects as $dataObject) {
                    yield $dataObject;
                }
            }
        } else {
            return $this->_generateFromArgs($stringQueryFilters);
        }
    }

    private function _generateFromArgs(array $args) {
        //todo implement with range.
        $api = $this->_apiClient;
        $token = $this->_token;
        $resource = $this->_resource . '?' . http_build_query($args);
        $response = $api->GET($resource, ['Authorization' => "Bearer $token"]);
        if ($response->isError()) {
            throw $response->asException();
        }
        $dataObjects = $this->_generateDataObjects($response);
        foreach($dataObjects as $dataObject){
            yield $dataObject;
        }
    }

    protected abstract function _generateDataObjects(RestResponse $response);


    public function next () {
        $this->_ensureGenerator();
        return $this->_generator->next();
    }

    public function valid () {
        $this->_ensureGenerator();
        return $this->_generator->valid();
    }

    public function current () {
        $this->_ensureGenerator();
        return $this->_generator->current();
    }

    public function rewind () {
        $this->_ensureGenerator();
        return $this->_generator->rewind();
    }

    public function key () {
        $this->_ensureGenerator();
        return $this->_generator->key();
    }

}
