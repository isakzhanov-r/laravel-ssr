<?php

namespace IsakzhanovR\Ssr\Exceptions;

use Symfony\Component\Process\Exception\RuntimeException;
use Throwable;

class NodeNotFoundException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
