<?php

namespace App\Services\Bigcommerce\Request;

class FetchCategoryProductsSortOrderRequest extends AbstractRequest
{

    /**
     * Get Categories Tree
     *
     * @param string $clientId
     * @param string $accessToken
     * @param string $storeHash
     * @param string $categoryId
     */
    public function __construct(string $clientId, string $accessToken, string $storeHash, int $categoryId, array $queryParams = [])
    {
        parent::__construct($clientId, $accessToken, $storeHash, $queryParams);

        $this->setResourceId($categoryId);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/categories/' . $this->getResourceId() . '/products/sort-order';
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
