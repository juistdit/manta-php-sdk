<?php
declare(strict_types=1);

namespace Manta\DataObjects\QuerySets;


use Manta\DataObjects\Objects\Order;
use Manta\Exceptions\ApiException;
use Manta\Rest\RestResponse;

class OrderQuerySet extends QuerySet
{

    private static $_allowedStatuses = null;
    public function getAllowedStatuses() {
        return ['new', 'invoiced', 'shipped', 'complete', 'canceled'];//TBD use the code below
        if(self::$_allowedStatuses === null){
            $response = $this->_apiClient->GET("brand/orders?status=invalid_status", ['Authorization' => "Bearer " . $this->_token]);
            if($response->isError()){
                var_dump($response->body);
                if(preg_match("/allowed (statuses)? are (?P<allowed_statuses>[a-zA-Z, ]*)/", $response->asException()->getMessage(), $output_array)){
                    self::$_allowedStatuses = array_map('trim', explode(',', $output_array['allowed_statuses']));
                    var_dump($output_array);
                    var_dump(self::$_allowedStatuses);
                    return self::$_allowedStatuses;
                }
            }
            throw new ApiException("Couldn't request the allowed statuses");
        }
        return self::$_allowedStatuses;
    }

    public function __construct ($apiClient, $resource, $token, $queryFilters) {
        parent::__construct ($apiClient, $resource, $token, $queryFilters);
    }

    /*public function dateBefore(\DateTime $dt) {
        $class = get_class($this);
        return new $class($this->_apiClient, $this->_resource, $this->_token, array_merge($this->_queryFilters, ['end' => $dt->format(DATE_ATOM)]));

    }

    public function dateAfter(\DateTime $dt){
        $class = get_class($this);
        return new $class($this->_apiClient, $this->_resource, $this->_token, array_merge($this->_queryFilters, ['start' => $dt->format(DATE_ATOM)]));
    }*/

    public function statusEqualTo(string $status) {
        return $this->statusIn([$status]);
    }

    public function statusNot(string $status) {
        $list = $this->getAllowedStatuses();
        $list = array_filter($list, function($s) use($status) { return $s !== $status;});
        return $this->statusIn($list);
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
        return array_map(function($data){return new Order($data);}, $response->body['orders']);
    }
}