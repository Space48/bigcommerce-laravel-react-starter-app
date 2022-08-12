<?php

namespace Tests\Unit\Services\Bigcommerce;

use App\Services\Bigcommerce\Request\AbstractRequest;
use App\Services\Bigcommerce\Response;
use Illuminate\Http\Client\Response as HttpResponse;
use Tests\TestCase;

class ResponseTest extends TestCase
{

    public function testValidResponse()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => true,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => ['data' => []],
            'status' => 200,
            'successful' => true,
        ]);

        $response = new Response($request, $httpResponse);
        $this->assertIsArray($response->getData());
    }

    public function testEmptyResponse()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => false,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => null,
            'status' => 200,
            'successful' => true,
        ]);

        $response = new Response($request, $httpResponse);
        $this->assertNull($response->getData());
    }

    public function testNoDataEntryForV3Endpoint()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => true,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);


        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => [],
            'status' => 200,
            'successful' => true,
        ]);

        $this->expectException(\Exception::class);
        new Response($request, $httpResponse);
    }

    public function testNoExceptionWhenMissingDataOnV2Endpoint()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => false,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => [],
            'status' => 200,
            'successful' => true,
        ]);

        $response = new Response($request, $httpResponse);
        $this->assertIsArray($response->getData());
    }

    public function testUnauthenticatedResponse()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => false,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => ['status' => '', 'title' => ''],
            'status' => 401,
            'successful' => false,
        ]);

        $this->expectException(Response\UnauthorizedException::class);
        new Response($request, $httpResponse);
    }

    public function testNotFoundException()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => true,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => ['status' => '', 'title' => ''],
            'status' => 404,
            'successful' => false,
        ]);

        $this->expectException(Response\NotFoundException::class);
        new Response($request, $httpResponse);
    }

    public function testTooManyRequestsException()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => true,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => ['status' => '', 'title' => ''],
            'status' => 429,
            'successful' => false,
            'header' => '23556',
        ]);

        try {
            new Response($request, $httpResponse);
            $this->fail('TooManyRequestsException was not thrown');
        } catch (Response\TooManyRequestsException $e) {
            $this->assertEquals(24, $e->getRetryAfter());
        }
    }

    public function testOtherFailure()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => true,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => ['status' => '', 'title' => 'This was the error'],
            'status' => 502,
            'successful' => false,
        ]);

        try {
            new Response($request, $httpResponse);
            $this->fail('Failed request did not throw exception');
        } catch (Response\Exception $e) {
            $this->assertEquals(502, $e->getStatusCode());
            $this->assertEquals('This was the error', $e->getMessage());
        }
    }

    public function testStoreDoesNotExistResponseBody()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => true,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $error = 'Store 20xggkekhv does not exist';
        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => null,
            'body' => $error,
            'status' => 502,
            'successful' => false,
        ]);

        try {
            new Response($request, $httpResponse);
            $this->fail('Failed request did not throw exception');
        } catch (Response\Exception $e) {
            $this->assertEquals(502, $e->getStatusCode());
            $this->assertEquals($error, $e->getMessage());
        }
    }

    public function testStoreExpired()
    {
        $request = $this->createConfiguredMock(AbstractRequest::class, [
            'successfulResponseShouldIncludeDataAttribute' => true,
            'successfulResponseShouldIncludeMetaPaginationAttribute' => false,
        ]);

        $error = 'You can not perform the requested method on an expired store.';
        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => ['status' => 403, 'message' => $error],
            'status' => 502,
            'successful' => false,
        ]);

        try {
            new Response($request, $httpResponse);
            $this->fail('Failed request did not throw exception');
        } catch (Response\Exception $e) {
            $this->assertEquals(502, $e->getStatusCode());
            $this->assertEquals($error, $e->getMessage());
        }
    }

    public function testUnprocessableException()
    {
        $request = $this->createMock(AbstractRequest::class);

        $errorMessage = 'Please verify if all required fields are present in the request body and are filled with values correctly.';
        $httpResponse = $this->createConfiguredMock(HttpResponse::class, [
            'json' => ['status' => 422, 'title' => $errorMessage],
            'status' => 422,
            'successful' => false,
        ]);

        try {
            new Response($request, $httpResponse);
            $this->fail('Expected to throw exception');
        } catch (Response\UnprocessableException $e) {
            $this->assertEquals(422, $e->getStatusCode());
            $this->assertEquals($errorMessage, $e->getMessage());
        }
    }
}
