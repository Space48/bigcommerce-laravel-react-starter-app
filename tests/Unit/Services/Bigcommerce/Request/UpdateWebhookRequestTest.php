<?php

namespace Tests\Unit\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\UpdateWebhookRequest;
use Tests\TestCase;

class UpdateWebhookRequestTest extends TestCase
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
        $webhookId = '123456';
        $webhook = [
            'scope' => 'store/product/created',
            'destination' => 'http://example.com',
            'is_active' => true,
            'headers' => [
                'auth' => 'secret'
            ]
        ];

        $request = new UpdateWebhookRequest($clientId, $accessToken, $storeHash, $webhookId, $webhook);

        $this->assertEquals("https://api.bigcommerce.com/stores/$storeHash/v3/hooks/$webhookId", $request->getEndpoint());
        $this->assertEquals('PUT', $request->getHttpMethod());
        $this->assertEquals($authHeaders, $request->getHeaders());
        $this->assertEquals($webhook, $request->getData());
    }
}
