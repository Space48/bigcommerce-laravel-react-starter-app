<?php

namespace Tests\Feature;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BigcommerceStoreUserRelationshipTest extends TestCase
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
        $user = $owner->user;
        $this->assertInstanceOf(User::class, $user);
    }
}
