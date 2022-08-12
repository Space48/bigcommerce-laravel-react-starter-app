<?php

namespace Tests\Unit\Models;

use App\Models\BigcommerceStore;
use Tests\TestCase;

class BigcommerceStoreTest extends TestCase
{
    public function testGetStoreHashFromContext()
    {
        $context = 'stores/abcde';
        $this->assertEquals('abcde', BigcommerceStore::getStoreHashFromContext($context));
    }

    public function testGetStoreHashFromInvalidContext()
    {
        $context = 'abcde';

        $this->expectException(\InvalidArgumentException::class);
        BigcommerceStore::getStoreHashFromContext($context);
    }

    public function testTimezoneOffsetPropertyNoDST()
    {
        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->make([
            'timezone_dst_correction' => false,
        ]);

        $this->assertEquals($store->timezone_raw_offset, $store->timezone_offset);
    }

    public function testTimezoneOffsetPropertyDST()
    {
        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->make([
            'timezone_dst_correction' => true,
        ]);

        $this->assertEquals($store->timezone_raw_offset + $store->timezone_dst_offset, $store->timezone_offset);
    }

    public function testTimezoneOffsetPropertyNoInfo()
    {
        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->make([
            'timezone_dst_correction' => null,
            'timezone_dst_offset' => null,
            'timezone_raw_offset' => null,
        ]);

        $this->assertEquals(null, $store->timezone_offset);
    }
}
