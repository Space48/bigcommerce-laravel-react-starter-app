<?php

namespace App\Services\Bigcommerce\Request;

class UpdateCategoryMetafieldRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, string $categoryId, string $metafieldId , array $metafield)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setResourceId($categoryId);
        $this->setSubresourceId($metafieldId);
        $this->setData($metafield);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/categories/' . $this->getResourceId() . '/metafields/' . $this->getSubresourceId();
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }
}
