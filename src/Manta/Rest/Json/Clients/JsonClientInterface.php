<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 09-02-17
 * Time: 12:33
 */

namespace Manta\Rest\Json\Clients;


interface JsonClientInterface
{
    public function __construct(string $api_url);

    public function GET($url, $headers = []);

    public function PATCH($url, $data, $headers = []);

    public function POST($url, $data, $headers = []);

    public function PUT($url, $data, $headers = []);

    public function DELETE($url, $headers = []);

    public function HEAD($url, $headers = []);
}