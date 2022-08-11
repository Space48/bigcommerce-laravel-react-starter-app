<?php

namespace App\Services\Bigcommerce\Request;

class FetchCategoryRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, string $categoryId)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setResourceId($categoryId);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/categories/' . $this->getResourceId();
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
