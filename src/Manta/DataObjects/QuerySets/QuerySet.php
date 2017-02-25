<?php
declare(strict_types=1);

namespace Manta\DataObjects\QuerySets;

use Manta\Rest\RestResponse;

abstract class QuerySet implements \Iterator {

    /**
     * @var \Manta\Rest\JsonClientInterface
     */
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
            $this->_generator = $this->_generatorNoArgs();
        }
    }

    private function _generatorNoArgs() {
        $arrayQueryFilters = array_filter($this->_queryFilters, 'is_array');
        $stringQueryFilters = array_filter($this->_queryFilters, 'is_string');
        if(count($arrayQueryFilters) > 0) {
            return $this->_generatorArraysAndStrings($arrayQueryFilters, $stringQueryFilters);
        } else {
            return $this->_generatorStrings($stringQueryFilters);
        }
    }

    //returns a generator
    private function _generatorArraysAndStrings(array $arrayQueryFilters = null, array $stringQueryFilters = null){
        $allFilter = end($arrayQueryFilters);
        $key = key($arrayQueryFilters);
        array_pop($arrayQueryFilters);
        foreach($allFilter as $value){
            if(count($arrayQueryFilters) > 0){
                $dataObjects = $this->_generatorArraysAndStrings($arrayQueryFilters, array_merge($stringQueryFilters, [$key => $value]));
            } else {
                $dataObjects = $this->_generatorStrings($stringQueryFilters);
            }
            //or yield from:
            foreach($dataObjects as $dataObject) {
                yield $dataObject;
            }
        }
    }

    private function _generatorStrings(array $args) {
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
