<?php

namespace IsakzhanovR\Ssr\Exceptions;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Process\Exception\RuntimeException;
use Throwable;

class NodeNotFoundException extends RuntimeException
{
    #[Pure] public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
