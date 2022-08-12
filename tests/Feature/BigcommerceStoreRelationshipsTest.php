<?php

namespace Tests\Feature;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BigcommerceStoreRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function testOwnerRelationship()
    {

        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->create();

        /** @var BigcommerceStoreUser $owner */
        $owner = BigcommerceStoreUser::factory()->create([
            'is_owner' => true,
            'bigcommerce_store_id' => $store->id
        ]);

        $anotherUser = BigcommerceStoreUser::factory()->create([
            'is_owner' => false,
            'bigcommerce_store_id' => $store->id
        ]);

        $this->assertCount(2, $store->users);
        $this->assertCount(1, $store->owners);

        $this->assertEquals($owner->id, $store->getOwner()->id);
    }
}
