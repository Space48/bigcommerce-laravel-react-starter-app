<?php

namespace Tests\Feature\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Traits\Bigcommerce\CanAddStoreUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanAddStoreUserTest extends TestCase
{
    use RefreshDatabase;

    private $user = [
        'bigcommerce_user_id' => 123456,
        'email' => 'test@example.com',
        'bigcommerce_owner_id' => 987654,
    ];

    /**
     * Test that a new user can be added to a store.
     *
     * @return void
     */
    public function testAddStoreUser()
    {
        /** @var CanAddStoreUser $trait */
        $trait = $this->getMockForTrait(CanAddStoreUser::class);

        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->create();

        $user = $trait->addStoreUser(
            $this->user['bigcommerce_user_id'],
            $this->user['email'],
            $store,
            $this->user['bigcommerce_owner_id']
        );

        $this->assertDatabaseHas('users',
            [
                'email' => $this->user['email'],
                'bigcommerce_id' => $this->user['bigcommerce_user_id'],
            ]
        );

        $this->assertDatabaseHas('bigcommerce_store_users',
            [
                'user_id' => $user->id,
                'bigcommerce_store_id' => $store->id,
                'is_owner' => false,
            ]
        );
    }

    /**
     * Add store user but confirm that user added as owner.
     *
     * @return void
     */
    public function testAddStoreOwner()
    {
        /** @var CanAddStoreUser $trait */
        $trait = $this->getMockForTrait(CanAddStoreUser::class);

        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->create();

        $user = $trait->addStoreUser(
            $this->user['bigcommerce_owner_id'],
            $this->user['email'],
            $store,
            $this->user['bigcommerce_owner_id']
        );

        $this->assertDatabaseHas('users',
            [
                'email' => $this->user['email'],
                'bigcommerce_id' => $this->user['bigcommerce_owner_id'],
            ]
        );

        $this->assertDatabaseHas('bigcommerce_store_users',
            [
                'user_id' => $user->id,
                'bigcommerce_store_id' => $store->id,
                'is_owner' => true,
            ]
        );
    }
}
