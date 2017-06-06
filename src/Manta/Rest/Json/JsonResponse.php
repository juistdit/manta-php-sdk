<?php
//declare(strict_types=1);

namespace Manta\Rest\Json;


use Manta\Rest\RestResponse;

class JsonResponse extends RestResponse
{

    public function __construct(array $opts = null) {
        if(isset($opts['status'])) {
            $this->status = $opts['status'];
        }
        if(isset($opts['raw_body'])) {
            $raw_body = $opts['raw_body'];
            //split header and body
           if ( $this->status != '200') {
//               echo 'Process Failed';
 //              var_dump($raw_body);
           }
            //try {
                list($headers, $body) = explode("\r\n\r\n", $raw_body, 2);

                /* Sometimes an extra header is send: HTTP/1.1 100 Continue */
                json_decode($body);
                if (json_last_error() != JSON_ERROR_NONE) {
                    list($headers_1, $headers, $body) = explode("\r\n\r\n", $raw_body, 3);
                    json_decode($body);
                    if (json_last_error() != JSON_ERROR_NONE) {
   //                     echo 'No json returned';
     //                   var_dump($raw_body);
              //          die();
                    }
                };
            //}
            //catch (Exception $e) {
            //    var_dump($raw_body);
            //    die();
            //}
            //parse headers
            $headers = explode("\r\n", $headers);
            //remove http/1.1 header
            array_shift($headers);
            //split into key and value values
            $headers = array_map(function($v){return explode(":", $v, 2);}, $headers);
            //zip the tuples into an associative array
            $keys = array_map('strtolower', array_column($headers, 0));
            $values = array_column($headers, 1);
            $headers = array_combine($keys, $values);

            $this->headers = $headers;
            $this->raw_body = $body;
            $this->body = json_decode($body, true);
        }
    }

}