<?php

namespace App\Services\Bigcommerce\Request;

class FetchProductRequest extends AbstractRequest
{

    public function __construct(string $clientId, string $accessToken, string $storeHash, string $productId, array $queryParams = [])
    {
        parent::__construct($clientId, $accessToken, $storeHash, $queryParams);

        $this->setResourceId($productId);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/products/' . $this->getResourceId();
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
