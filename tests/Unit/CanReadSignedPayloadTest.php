<?php

namespace Tests\Unit;

use App\Traits\Bigcommerce\CanReadSignedPayload;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

class CanReadSignedPayloadTest extends TestCase
{
    /**
     * Test that a new user can be added to a store.
     *
     * @return void
     */
    public function testReadSignedPayload()
    {
        /** @var CanReadSignedPayload $trait */
        $trait = $this->getMockForTrait(CanReadSignedPayload::class);

        $testSharedSecret = '123456789abc';
        $testPayload = 'eyJmb28iOiJiYXIifQ==.ZTczODViZmFmNDFiMzg5MmEzYTY1MWUzZTI3MzAzY2E2NzE0YmYwZjcwZjBlNjgwODVjZjBmZGY4ZmRjNGYxZg==';
        $expectedData = ['foo' => 'bar'];

        $payload = $trait->readPayload($testPayload, $testSharedSecret);
        $this->assertEquals($expectedData, $payload);
    }

    public function testThrowErrorOnInvalidPayload()
    {
        /** @var CanReadSignedPayload $trait */
        $trait = $this->getMockForTrait(CanReadSignedPayload::class);

        $testSharedSecret = 'wrong-key';
        $testPayload = 'eyJmb28iOiJiYXIifQ==.ZTczODViZmFmNDFiMzg5MmEzYTY1MWUzZTI3MzAzY2E2NzE0YmYwZjcwZjBlNjgwODVjZjBmZGY4ZmRjNGYxZg==';

        $this->expectException(UnexpectedValueException::class);

        $trait->readPayload($testPayload, $testSharedSecret);
    }
}
