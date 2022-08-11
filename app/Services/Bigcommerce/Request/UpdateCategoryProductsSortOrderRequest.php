<?php

namespace App\Services\Bigcommerce\Request;

class UpdateCategoryProductsSortOrderRequest extends AbstractRequest
{

    public function __construct(string $clientId, string $accessToken, string $storeHash, int $categoryId, array $productSortOrders)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setResourceId($categoryId);

        $this->setData($this->transformProductSortOrders($productSortOrders));
    }

    /**
     * Ensure product sort orders are in the right format.
     */
    private function transformProductSortOrders(array $productSortOrders): array
    {
        return array_map(function ($productSortOrder) {
            $sortOrder = $productSortOrder['sort_order'];
            if (is_string($sortOrder)) {
                $sortOrder = $productSortOrder['sort_order'] === '' ? null : (int)$productSortOrder['sort_order'];
            }
            return [
                'product_id' => (int)$productSortOrder['product_id'],
                'sort_order' => $sortOrder
            ];
        }, $productSortOrders);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/catalog/categories/' . $this->getResourceId() . '/products/sort-order';
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }

    public function successfulResponseShouldIncludeDataAttribute(): bool
    {
        return false;
    }

    public function successfulResponseShouldIncludeMetaPaginationAttribute(): bool
    {
        return false;
    }
}
