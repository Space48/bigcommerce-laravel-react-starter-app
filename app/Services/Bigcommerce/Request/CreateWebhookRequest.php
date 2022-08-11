<?php

namespace App\Services\Bigcommerce\Request;

class CreateWebhookRequest extends AbstractRequest
{

    public function __construct(string $clientId, string $accessToken, string $storeHash, array $webhook)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setData($webhook);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/hooks';
    }

    public function getHttpMethod(): string
    {
        return 'POST';

    }
}
