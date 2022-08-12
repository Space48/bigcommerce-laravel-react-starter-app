<?php

namespace Tests\Feature\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use App\Traits\Bigcommerce\CanManageOwner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanManageOwnerTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateOwner()
    {
        /** @var CanManageOwner $trait */
        $trait = $this->getMockForTrait(CanManageOwner::class);

        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->create();
        $storeUser = BigcommerceStoreUser::factory()->create(
            ['bigcommerce_store_id' => $store->id, 'is_owner' => false]
        );

        $trait->updateOwner($storeUser->user->bigcommerce_id, $store);

        $this->assertDatabaseHas('bigcommerce_store_users',
            [
                'user_id' => $storeUser->user->id,
                'bigcommerce_store_id' => $store->id,
                'is_owner' => true,
            ]
        );
    }

    public function testUnrecognisedOwner()
    {
        /** @var CanManageOwner $trait */
        $trait = $this->getMockForTrait(CanManageOwner::class);

        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->create();
        $storeUser = BigcommerceStoreUser::factory()->create(
            ['bigcommerce_store_id' => $store->id, 'is_owner' => true]
        );

        $trait->updateOwner('-1', $store);

        $this->assertDatabaseHas('bigcommerce_store_users',
            [
                'user_id' => $storeUser->user->id,
                'bigcommerce_store_id' => $store->id,
                'is_owner' => false,
            ]
        );
    }

}
