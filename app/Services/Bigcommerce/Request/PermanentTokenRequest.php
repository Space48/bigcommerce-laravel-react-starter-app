<?php

namespace App\Services\Bigcommerce\Request;

class PermanentTokenRequest extends AbstractRequest
{

    public function __construct(
        string $clientId,
        string $clientSecret,
        string $installUrl,
        string $code,
        string $scope,
        string $context,
        string $authorizationCode = 'authorization_code'
    )
    {
        $this->setData(
            [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $installUrl,
                'grant_type' => $authorizationCode,
                'code' => $code,
                'scope' => $scope,
                'context' => $context,
            ]
        );
    }

    public function getPath(): string
    {
        return $this->authUrl;
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }
}
