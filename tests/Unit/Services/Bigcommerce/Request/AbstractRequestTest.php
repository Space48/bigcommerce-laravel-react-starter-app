<?php

namespace Tests\Unit\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Request\AbstractRequest;
use Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    protected $request;


    public function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createPartialMock(AbstractRequest::class, ['getPath', 'getHttpMethod', 'getResourceId']);
    }

    public function testSuccessfulResponseShouldIncludeDataAttribute()
    {
        $this->request->method('getPath')
            ->willReturn('/v3/');

        $this->request->method('getHttpMethod')
            ->willReturn('GET');

        $this->assertTrue($this->request->successfulResponseShouldIncludeDataAttribute());
    }

    public function testSuccessfulResponseMightNotIncludeDataAttribute()
    {
        $this->request->method('getPath')
            ->willReturn('/v2/');

        $this->request->method('getHttpMethod')
            ->willReturn('GET');

        $this->assertFalse($this->request->successfulResponseShouldIncludeDataAttribute());

        $this->request->method('getPath')
            ->willReturn('/v3/');

        $this->request->method('getHttpMethod')
            ->willReturn('DELETE');

        $this->assertFalse($this->request->successfulResponseShouldIncludeDataAttribute());
    }

    public function testSuccessfulResponseShouldIncludeMetaPaginationAttribute()
    {
        $this->request->method('getPath')
            ->willReturn('/v3/');

        $this->request->method('getResourceId')
            ->willReturn('');

        $this->request->method('getHttpMethod')
            ->willReturn('GET');

        $this->assertTrue($this->request->successfulResponseShouldIncludeMetaPaginationAttribute());
    }

    public function testSuccessfulResponseMightNotIncludeMetaPaginationAttribute()
    {
        $this->request->method('getPath')
            ->willReturn('/v2/');

        $this->request->method('getResourceId')
            ->willReturn('');

        $this->request->method('getHttpMethod')
            ->willReturn('GET');

        $this->assertFalse($this->request->successfulResponseShouldIncludeMetaPaginationAttribute());

        $this->request->method('getPath')
            ->willReturn('/v3/');

        $this->request->method('getResourceId')
            ->willReturn('2345');

        $this->request->method('getHttpMethod')
            ->willReturn('GET');

        $this->assertFalse($this->request->successfulResponseShouldIncludeMetaPaginationAttribute());

        $this->request->method('getPath')
            ->willReturn('/v3/');

        $this->request->method('getResourceId')
            ->willReturn('');

        $this->request->method('getHttpMethod')
            ->willReturn('POST');

        $this->assertFalse($this->request->successfulResponseShouldIncludeMetaPaginationAttribute());
    }
}
