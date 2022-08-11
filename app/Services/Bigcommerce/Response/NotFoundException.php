<?php

namespace App\Services\Bigcommerce\Response;

use Throwable;

class NotFoundException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(404, $message, $code, $previous);
    }
}
