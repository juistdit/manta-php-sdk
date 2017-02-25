<?php
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 25-02-17
 * Time: 15:29
 */

namespace Manta\Rest;


interface RestClientInterface
{
    public function __construct(string $api_url);

    public function GET($url, $headers = []) ;

    public function PATCH($url, $data, $headers = []) ;

    public function POST($url, $data, $headers = []) ;

    public function PUT($url, $data, $headers = []) ;

    public function DELETE($url, $headers = []) ;

    public function HEAD($url, $headers = []) ;
}