<?php

namespace Tests\Unit\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\FetchStoreInformationRequest;
use PHPUnit\Framework\TestCase;

class FetchStoreInformationRequestTest extends TestCase
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

        $request = new FetchStoreInformationRequest($clientId, $accessToken, $storeHash);

        $this->assertEquals("https://api.bigcommerce.com/stores/$storeHash/v2/store", $request->getEndpoint());
        $this->assertEquals($authHeaders, $request->getHeaders());
        $this->assertEmpty($request->getData());
    }
}
