<?php

namespace App\Services\Bigcommerce\Response;

use Illuminate\Support\Facades\Log;
use Throwable;

class UnprocessableException extends Exception
{
    public function __construct(protected $errors = [], $message = "", $code = 0, Throwable $previous = null)
    {
        Log::info('ERRORS', $errors);
        parent::__construct(422, $message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

}
