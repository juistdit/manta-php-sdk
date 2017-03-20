<?php
//declare(strict_types=1);

namespace Manta\Exceptions;
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 15-02-17
 * Time: 12:41
 *
 * General exceptions, for instance when curl gives errors
 * RestException itself should not occur in a correct setting, MantaApiExceptions can occur more often though.
 */

class RestException extends \Exception {

}