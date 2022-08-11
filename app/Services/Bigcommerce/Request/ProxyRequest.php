<?php

namespace App\Services\Bigcommerce\Request;

class ProxyRequest extends AbstractRequest
{

    public function __construct(
        string $clientId,
        string $accessToken,
        string $method,
        string $storeHash,
        string $endpoint,
        array  $queryParams = [],
               $data = null
    )
    {
        parent::__construct($clientId, $accessToken, $storeHash, $queryParams);

        $this->setParameter('raw_endpoint', $endpoint);
        $this->setParameter('raw_method', $method);

        if (isset ($data)) {
            $this->setData($data);
        }
    }

    public function getPath(): string
    {
        return $this->apiBaseUrl . $this->getStoreHash() . '/' . $this->getParameter('raw_endpoint');
    }

    public function getHttpMethod(): string
    {
        return $this->getParameter('raw_method');
    }
}
