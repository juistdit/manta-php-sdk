<?php
declare(strict_types=1);

namespace Manta\DataObjects\QuerySets;


use Manta\DataObjects\Objects\Order;
use Manta\Rest\RestResponse;

class OrderQuerySet extends QuerySet
{

    const STATUS_VALUES = ['waiting', 'new', 'inprogress', 'inprogressaction', 'complete', 'canceled'];

    public function __construct ($apiClient, $resource, $token, $queryFilters) {
        parent::__construct ($apiClient, $resource, $token, $queryFilters);
    }

    public function dateBefore(\DateTime $dt) {
        $class = get_class($this);
        return new $class($this->_apiClient, $this->_resource, $this->_token, array_merge($this->_queryFilters, ['end' => $dt->format(DATE_ATOM)]));

    }

    public function dateAfter(\DateTime $dt){
        $class = get_class($this);
        return new $class($this->_apiClient, $this->_resource, $this->_token, array_merge($this->_queryFilters, ['start' => $dt->format(DATE_ATOM)]));
    }

    public function statusEqualTo(string $status) {
        return $this->statusIn([$status]);
    }

    public function statusNot(string $status) {
        $list = self::STATUS_VALUES;
        $list = array_filter(function($s) use($status) { return $s !== $status;}, $list);
        return $this->statusIn($status);
    }

    public function statusIn(array $status) {
        $class = get_class($this);
        if (isset($this->_queryFilters['status'])) {
            $status = array_intersect($this->_queryFilters['status'], $status);
        }
        return new $class($this->_apiClient, $this->_resource, $this->_token, array_merge($this->_queryFilters, ['status' => $status]));
    }

    protected function _generateDataObjects(RestResponse $response)
    {
        return array_map(function($data){return new Order($data);}, $response->body['order']);
    }
}