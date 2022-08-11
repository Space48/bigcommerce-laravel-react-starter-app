<?php

namespace App\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\Payload\WidgetTemplatePayload;

class UpdateWidgetTemplateRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, string $widgetTemplateId, WidgetTemplatePayload $payload)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setResourceId($widgetTemplateId);
        $this->setData($payload->toArray());
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v3/content/widget-templates/' . $this->getResourceId();
    }

    public function getHttpMethod(): string
    {
        return 'PUT';
    }
}
