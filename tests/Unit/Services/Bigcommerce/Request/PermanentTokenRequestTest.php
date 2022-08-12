<?php

namespace Tests\Unit\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\PermanentTokenRequest;
use PHPUnit\Framework\TestCase;

class PermanentTokenRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateRequest()
    {
        $clientId = 'testClientId';
        $clientSecret = 'testClientSecret';
        $installUrl = 'https://install.url/';
        $code = 'testCode';
        $scope = 'testScope';
        $context = 'testContext';
        $authHeaders = [];

        $request = new PermanentTokenRequest(
            $clientId,
            $clientSecret,
            $installUrl,
            $code,
            $scope,
            $context
        );

        $this->assertEquals("https://login.bigcommerce.com/oauth2/token", $request->getEndpoint());
        $this->assertEquals($authHeaders, $request->getHeaders());
        $this->assertEquals(
            [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $installUrl,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'scope' => $scope,
                'context' => $context,
            ],
            $request->getData()
        );
    }
}
