<?php

namespace Tests\Feature\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use App\Traits\Bigcommerce\CanRemoveStoreUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanRemoveStoreUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that store access can be removed for a user.
     *
     * @return void
     */
    public function testRemoveStoreUser()
    {
        /** @var CanRemoveStoreUser $trait */
        $trait = $this->getMockForTrait(CanRemoveStoreUser::class);

        // Test setup, add store, user and relationship.
        $store = BigcommerceStore::factory()->create();
        $storeUser = BigcommerceStoreUser::factory()->create(['bigcommerce_store_id' => $store->id, 'is_owner' => true]);

        // Confirm data saved.
        $this->assertDatabaseHas('users',
            [
                'email' => $storeUser->user->email,
                'bigcommerce_id' => $storeUser->user->bigcommerce_id
            ]
        );

        $this->assertDatabaseHas('bigcommerce_store_users',
            [
                'user_id' => $storeUser->user->id,
                'bigcommerce_store_id' => $store->id,
                'is_owner' => true
            ]
        );

        // Remove user access to store
        $trait->removeStoreUser($storeUser->user->bigcommerce_id, $store);

        // Confirm user still exists
        $this->assertDatabaseHas('users',
            [
                'email' => $storeUser->user->email,
                'bigcommerce_id' => $storeUser->user->bigcommerce_id
            ]);

        // But they should not have access to the store.
        $this->assertDatabaseMissing('bigcommerce_store_users',
            [
                'user_id' => $storeUser->user->id,
                'bigcommerce_store_id' => $store->id,
                'is_owner' => true
            ]);
    }
}
