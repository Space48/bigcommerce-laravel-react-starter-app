<?php

namespace Tests\Feature\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Models\User;
use App\Traits\Bigcommerce\CanInstallStore;
use App\Traits\Bigcommerce\CanUninstallStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CanInstallStoreTest extends TestCase
{
    use RefreshDatabase;

    private $store = [
        'context' => 'stores/mwefr3456',
        'store_hash' => 'mwefr3456',
        'scope' => 'store_v2_content store_v2_default',
        'access_token' => 'eyJpdiI6InRZVXhEUHResf43459FZEI0R1lndFE9PSIsInZhbHVlIjoieHNRT3BnQm84cENpR05SVUhrTUdNK3M2WVNFSVIwR2dXZm5LRTU2aFAzamNRMUpmbVhnSlUyY3NFVDNCSkxnOCIsIm1hYyI6Ijk4YzY5MjUwY2YyMDM2OTJkNWZjMmExNjA1MDBlOWEyZWNjMzE3NjMyNWQ3MzliNGYwMDdmNWRiZTZhMTQ1YjUifQ==',
        'owner_id' => 234565,
        'owner_email' => 'owner@example.com'
    ];

    /**
     * Test store installation
     *
     * @return void
     */
    public function testInstallStore()
    {
        /** @var CanInstallStore $trait */
        $trait = $this->getMockForTrait(CanInstallStore::class);

        $store = $trait->installStore(
            $this->store['context'],
            $this->store['scope'],
            $this->store['access_token'],
            $this->store['owner_id'],
            $this->store['owner_email']
        );

        // Confirm store created in database
        $this->assertDatabaseHas('bigcommerce_stores',
            [
                'store_hash' => $this->store['store_hash'],
                'scope' => $this->store['scope'],
                'installed' => true
            ]
        );

        // Confirm access token accessible
        $store = BigcommerceStore::where('store_hash', $this->store['store_hash'])->first();
        $this->assertEquals($store->access_token, $this->store['access_token']);

        // Confirm user created.
        $this->assertDatabaseHas('users',
            [
                'bigcommerce_id' => $this->store['owner_id'],
                'email' => $this->store['owner_email']
            ]
        );

        // Confirm mapping between store and user is created
        $user = User::where('bigcommerce_id', $this->store['owner_id'])->first();
        $this->assertDatabaseHas('bigcommerce_store_users',
            [
                'user_id' => $user->id,
                'bigcommerce_store_id' => $store->id,
                'is_owner' => true
            ]
        );
    }

    /**
     * Test store installation after being uninstalled
     *
     * @return void
     */
    public function testStoreReinstallation()
    {
        /** @var CanInstallStore $installTrait */
        $installTrait = $this->getMockForTrait(CanInstallStore::class);

        // Install store
        $store = $installTrait->installStore(
            $this->store['context'],
            $this->store['scope'],
            $this->store['access_token'],
            $this->store['owner_id'],
            $this->store['owner_email']
        );

        Event::fake();
        
        // Uninstall store
        /** @var CanUninstallStore $uninstallTrait */
        $uninstallTrait = $this->getMockForTrait(CanUninstallStore::class);
        $uninstallTrait->uninstallStore($this->store['store_hash']);

        // Reinstall store
        $store = $installTrait->installStore(
            $this->store['context'],
            $this->store['scope'],
            $this->store['access_token'],
            $this->store['owner_id'],
            $this->store['owner_email']
        );

        // Confirm store re-installed
        $this->assertDatabaseHas('bigcommerce_stores',
            [
                'store_hash' => $this->store['store_hash'],
                'scope' => $this->store['scope'],
                'installed' => true
            ]
        );
    }
}
