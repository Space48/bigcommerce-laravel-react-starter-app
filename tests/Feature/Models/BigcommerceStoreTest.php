<?php

namespace Tests\Feature\Models;

use App\Models\BigcommerceStore;
use App\Models\Subscription;
use Carbon\Carbon;
use Tests\TestCase;

class BigcommerceStoreTest extends TestCase
{

    public function testActiveWithSubscription()
    {
        $store = $this->createStore();
        $this->assertTrue($store->active());
    }

    public function testInActiveWhenUninstalled()
    {
        $store = $this->createStore();
        $store->installed = false;
        $this->assertFalse($store->active());
    }

    private function createStore(): BigcommerceStore
    {
        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->create();
        return $store;
    }
}
