<?php

namespace App\Services\Bigcommerce\Response;

use Throwable;

class TooManyRequestsException extends Exception
{
    // The number of seconds to wait before retrying
    protected int $retryAfter;

    public function __construct(int $retryAfter, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct(429, $message, $code, $previous);

        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}
