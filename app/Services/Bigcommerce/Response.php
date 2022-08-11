<?php

namespace App\Services\Bigcommerce;

use App\Services\Bigcommerce\Request\RequestInterface;
use App\Services\Bigcommerce\Response\Exception;
use App\Services\Bigcommerce\Response\NotFoundException;
use App\Services\Bigcommerce\Response\ProductsNotAssignedToCategoryException;
use App\Services\Bigcommerce\Response\TooManyRequestsException;
use App\Services\Bigcommerce\Response\UnauthorizedException;
use App\Services\Bigcommerce\Response\UnprocessableException;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\Log;

class Response
{
    protected RequestInterface $request;
    protected HttpResponse $response;

    protected ?array $data;

    /**
     * @throws \Exception
     * @throws Exception
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function __construct(RequestInterface $request, HttpResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->data = $response->json();

        $this->validate();
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @throws \Exception
     * @throws Exception
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    protected function validate(): void
    {
        if ($this->response->status() === 401) {
            throw new UnauthorizedException($this->getErrorMessage());

        } elseif ($this->response->status() === 404) {
            throw new NotFoundException($this->getErrorMessage());

        } elseif ($this->response->status() === 422) {
            if (str_contains($this->getErrorMessage(), 'Please verify if all requested Products are assigned to the Category')) {
                throw new ProductsNotAssignedToCategoryException($this->getErrors(), $this->getErrorMessage());
            }
            throw new UnprocessableException($this->getErrors(), $this->getErrorMessage());
        } elseif ($this->response->status() === 429) {
            $retryAfter = $this->response->header('X-Rate-Limit-Time-Reset-Ms') ?? 30000;
            throw new TooManyRequestsException(ceil($retryAfter / 1000), $this->getErrorMessage());

        } elseif (!$this->successful()) {
            Log::error("Bigcommerce error response:\n" . $this->response->body());
            Log::error("Request that caused error:\n" . print_r($this->request->getData(), true));
            throw new Exception($this->response->status(), $this->getErrorMessage());
        }

        if ($this->request->successfulResponseShouldIncludeDataAttribute()
            && !array_key_exists('data', $this->getData())) {
            throw new \Exception('Expected \'data\' field not found in response to ' . get_class($this->request));
        }

        if ($this->request->successfulResponseShouldIncludeMetaPaginationAttribute()
            && !array_key_exists('pagination', $this->getData()['meta'])) {
            throw new \Exception('Expected \'meta.pagination\' field not found in response to ' . get_class($this->request));
        }
    }

    protected function getErrorMessage(): ?string
    {
        if ($this->successful()) {
            return null;
        }


        if (isset($this->data['title'])) {
            return $this->data['title'];
        }

        if (isset($this->data['message'])) {
            return $this->data['message'];
        }

        return $this->response->body();
    }

    protected function getErrors(): array
    {
        if ($this->successful()) return [];

        return $this->data['errors'] ?? [];
    }

    protected function successful(): bool
    {
        return $this->response->successful();
    }
}
