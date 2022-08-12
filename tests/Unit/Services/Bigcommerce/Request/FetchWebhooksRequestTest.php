<?php

namespace Tests\Unit\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\FetchWebhooksRequest;
use Tests\TestCase;

class FetchWebhooksRequestTest extends TestCase
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

        $request = new FetchWebhooksRequest($clientId, $accessToken, $storeHash);

        $this->assertEquals("https://api.bigcommerce.com/stores/$storeHash/v3/hooks/", $request->getEndpoint());
        $this->assertEquals($authHeaders, $request->getHeaders());
        $this->assertEmpty($request->getData());
    }
}
