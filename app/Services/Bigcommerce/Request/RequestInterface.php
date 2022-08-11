<?php

namespace App\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Response;

interface RequestInterface
{
    public function getHttpMethod(): string;

    public function getPath(): string;

    /**
     * Send request and get response
     */
    public function send(): Response;

    public function getData(): array;

    public function successfulResponseShouldIncludeDataAttribute(): bool;

    public function successfulResponseShouldIncludeMetaPaginationAttribute(): bool;
}
