<?php

namespace Tests\Unit\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\CreateWebhookRequest;
use Tests\TestCase;

class CreateWebhookRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateRequest()
    {
        $clientId = 'testClientId';
        $accessToken = 'testAccessToken';
        $storeHash = 'testStoreHash';
        $authHeaders = [
            'X-Auth-Client' => $clientId,
            'X-Auth-Token' => $accessToken
        ];
        $webhook = [
            'scope' => 'store/product/created',
            'destination' => 'http://example.com',
            'is_active' => true,
            'headers' => [
                'auth' => 'secret'
            ]
        ];

        $request = new CreateWebhookRequest($clientId, $accessToken, $storeHash, $webhook);

        $this->assertEquals("https://api.bigcommerce.com/stores/$storeHash/v3/hooks", $request->getEndpoint());
        $this->assertEquals($authHeaders, $request->getHeaders());
        $this->assertEquals($webhook, $request->getData());
    }
}
