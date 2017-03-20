<?php
//declare(strict_types=1);

namespace Manta\Rest\Json\Clients;


use Manta\Rest\Json\JsonResponse;
use Manta\Rest\RestClientInterface;

abstract class AbstractClient implements RestClientInterface
{

    abstract protected function sendRequest(string $method, string $url, array $headers = [], array $data = null);

    public function GET($url, $headers = []) {
        return $this->sendRequest('GET', $url, $headers);
    }

    public function PATCH($url, $data, $headers = []) {
        return $this->sendRequest('PATCH', $url, $headers, $data);
    }

    public function POST($url, $data, $headers = []) {
        return $this->sendRequest('POST', $url, $headers, $data);
    }

    public function PUT($url, $data, $headers = []){
        return $this->sendRequest('PUT', $url, $headers, $data);
    }

    public function DELETE($url, $headers = []){
        return $this->sendRequest('DELETE', $url, $headers);
    }

    public function HEAD($url, $headers = []){
        return $this->sendRequest('HEAD', $url, $headers);
    }
}