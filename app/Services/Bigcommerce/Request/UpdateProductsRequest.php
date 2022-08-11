<?php

namespace App\Services\Bigcommerce\Request;

class UpdateProductsRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, array $products, array $queryParams = [])
    {
        parent::__construct($clientId, $accessToken, $storeHash, $queryParams);

        $this->setData($products);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/products';
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }
}
