<?php

namespace App\Services\Bigcommerce\Request;

class UpdateWebhookRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, string $webhookId, array $webhook)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setResourceId($webhookId);


        $this->setData($webhook);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/hooks/' . $this->getResourceId();
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }
}
