<?php

namespace App\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\Payload\WidgetTemplatePayload;

class CreateWidgetTemplateRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, WidgetTemplatePayload $payload)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setData($payload->toArray());
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/content/widget-templates';
    }

    public function getHttpMethod(): string
    {
        return 'POST';

    }
}
