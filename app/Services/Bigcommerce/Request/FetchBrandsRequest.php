<?php

namespace App\Services\Bigcommerce\Request;

class FetchBrandsRequest extends AbstractRequest
{

    public function __construct(string $clientId, string $accessToken, string $storeHash, array $queryParams = [])
    {
        parent::__construct($clientId, $accessToken, $storeHash, $queryParams);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/brands';
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
