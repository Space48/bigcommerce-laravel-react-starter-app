<?php

namespace App\Traits\Bigcommerce;

use UnexpectedValueException;

trait CanReadSignedPayload
{
    /**
     * Verify payload and return data
     *
     * @param string $payload
     * @param string $key
     * @return array
     * @throws \InvalidArgumentException
     *
     */
    public function readPayload(string $payload, string $key): array
    {
        list($encodedData, $encodedSignature) = explode('.', $payload, 2);

        $signature = base64_decode($encodedSignature);
        $jsonPayload = base64_decode($encodedData);
        $expectedSignature = hash_hmac('sha256', $jsonPayload, $key);

        if (!hash_equals($expectedSignature, $signature)) {
            throw new UnexpectedValueException('Invalid signature');
        }

        return json_decode($jsonPayload, true, 512, JSON_THROW_ON_ERROR);
    }
}
