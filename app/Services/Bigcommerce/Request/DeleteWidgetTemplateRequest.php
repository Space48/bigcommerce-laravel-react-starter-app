<?php

namespace App\Services\Bigcommerce\Request;

class DeleteWidgetTemplateRequest extends AbstractRequest
{
    private string $uuid;

    public function __construct(string $clientId, string $accessToken, string $storeHash, string $uuid)
    {
        $this->uuid = $uuid;
        parent::__construct($clientId, $accessToken, $storeHash);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/content/widget-templates/' . $this->uuid;
    }

    public function getHttpMethod(): string
    {
        return 'DELETE';
    }
}
