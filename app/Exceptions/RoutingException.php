<?php

namespace Exceptions;

use Exception;

class RoutingException extends Exception
{
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }
}
?>