<?php

namespace App\Services\Bigcommerce\Request;

class CreateCategoryMetafieldRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, string $categoryId, array $metafield)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setResourceId($categoryId);
        $this->setData($metafield);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/categories/' . $this->getResourceId() . '/metafields';
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }
}
