<?php

namespace App\Services\Bigcommerce\Request;

class FetchCategoryTreeRequest extends AbstractRequest
{

    public function __construct(string $clientId, string $accessToken, string $storeHash)
    {
        parent::__construct($clientId, $accessToken, $storeHash);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/categories/tree';
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }

    public function successfulResponseShouldIncludeMetaPaginationAttribute(): bool
    {
        return false;
    }
}
