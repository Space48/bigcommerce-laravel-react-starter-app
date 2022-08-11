<?php

namespace App\Services\Bigcommerce\Request;

class FetchWidgetTemplatesRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, int $channelId)
    {
        parent::__construct($clientId, $accessToken, $storeHash, [
            'limit' => 100,
            'channel_id:in' => [$channelId],
        ]);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/content/widget-templates';
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
