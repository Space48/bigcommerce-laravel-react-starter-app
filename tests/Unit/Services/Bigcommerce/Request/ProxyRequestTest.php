<?php

namespace Tests\Unit\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\ProxyRequest;
use Tests\TestCase;

class ProxyRequestTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreateRequest()
    {
        $clientId = 'testClientId';
        $accessToken = 'testAccessToken';
        $method = 'PUT';
        $storeHash = 'testStoreHash';
        $authHeaders = [
            'X-Auth-Client' => $clientId,
            'X-Auth-Token' => $accessToken
        ];
        $endpoint = 'v3/catalog/categories/27';
        $query = ['include' => 'products'];
        $data = [ 'foo' => 'bar'];

        $request = new ProxyRequest($clientId, $accessToken, $method, $storeHash, $endpoint, $query, $data);

        $this->assertEquals("https://api.bigcommerce.com/stores/$storeHash/$endpoint?include=products", $request->getEndpoint());
        $this->assertEquals($authHeaders, $request->getHeaders());
        $this->assertEquals($data, $request->getData());
    }
}
