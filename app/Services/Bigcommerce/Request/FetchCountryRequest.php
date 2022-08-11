<?php

namespace App\Services\Bigcommerce\Request;

class FetchCountryRequest extends AbstractRequest
{
    public function __construct(string $clientId, string $accessToken, string $storeHash, string $countryId)
    {
        parent::__construct($clientId, $accessToken, $storeHash);

        $this->setResourceId($countryId);
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/v2/countries/' . $this->getResourceId();
    }

    public function getHttpMethod(): string
    {
        return 'GET';
    }
}
