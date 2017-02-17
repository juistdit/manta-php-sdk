<?php
declare(strict_types=1);

namespace Manta\Rest;

use Manta\Exceptions\ApiException;

abstract class RestResponse
{
    public $status;
    public $headers;
    public $body;
    public $raw_body;

    public function isError() {
        return $this->status > 299;
    }

    private static $_exceptionTranslationTable = [
        'DEFAULT' => '\Manta\Exceptions\ApiException',
        'Company not found attached with you.' => '\Manta\Exceptions\NoAccessException',
        'You did not sign in correctly or your account is temporarily disabled.' => '\Manta\Exceptions\AuthorizationException'
    ];

    private function _getExceptionType() {
        if(isset($this->body['message']) && isset(self::$_exceptionTranslationTable[$this->body['message']])) {
            return self::$_exceptionTranslationTable[$this->body['message']];
        }
        return self::$_exceptionTranslationTable['DEFAULT'];
    }

    private function _getExceptionMessage() {
        if (isset($this->body['message'])) {
            $message = $this->body['message'];
            if (isset($this->body['parameters'])) {
                foreach($this->body['parameters'] as $key => $value) {
                    $message = str_replace("%$key", json_encode($value), $message);
                }
            }
            return $message;
        } else {
            return "Something went wrong when communicating with the API";
        }
    }

    public function asException() {
        $code = $this->status;
        $exception_type = $this->_getExceptionType();
        $message = $this->_getExceptionMessage();
        return new $exception_type($message, $code);
    }
}