<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 09-02-17
 * Time: 12:33
 */

namespace Manta\Http\Clients;


class CurlClient extends AbstractClient implements HttpClientInterface
{

    public function sendRequest(string $method, string $url, array $headers = [], array $data = null){

    }
}