<?php
//declare(strict_types=1);

namespace Manta\DataObjects\QuerySets;


use Manta\DataObjects\Objects\Order;
use Manta\Exceptions\ApiException;
use Manta\Rest\RestResponse;

class OrderQuerySet extends QuerySet
{

    public function getAllowedStatuses() {
        $allowed_statuses = array();
        $allowed_statuses[] = 'wait_brand_confirm';
        $allowed_statuses[] = 'new';
        $allowed_statuses[] = 'in_progress';
        //$allowed_statuses[] = 'in_progress_action_needed';
        $allowed_statuses[] = 'complete';
        $allowed_statuses[] = 'in_progress_proforma';
        $allowed_statuses[] = 'in_progress_invoice';
        $allowed_statuses[] = 'in_progress_company';
        $allowed_statuses[] = 'in_progress_brand';
        $allowed_statuses[] = 'in_progress_manta';
        $allowed_statuses[] = 'in_progress_ship';
        $allowed_statuses[] = 'in_progress_ship_invoice_open';
        $allowed_statuses[] = 'in_progress_ship_invoice';
        $allowed_statuses[] = 'closed';
        $allowed_statuses[] = 'in_progress_ship';

        return $allowed_statuses;
    }

    public function getNotAllowedStatuses() {
        $not_allowed_statuses = array();
        $not_allowed_statuses[] = 'quote';
        $not_allowed_statuses[] = 'wait_company_confirm';
        $not_allowed_statuses[] = 'wait_manta_confirm';
        $not_allowed_statuses[] = 'canceled';
        $not_allowed_statuses[] = 'quote_canceled';
        $not_allowed_statuses[] = 'fraud';
        $not_allowed_statuses[] = 'holded';

        return $not_allowed_statuses;

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

    public function statusEqualTo($status) {
        return $this->statusIn([$status]);
    }

    public function statusNot($status) {
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