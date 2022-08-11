<?php

namespace App\Services\Bigcommerce\Response;

use Throwable;

class UnauthorizedException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(401, $message, $code, $previous);
    }
}
