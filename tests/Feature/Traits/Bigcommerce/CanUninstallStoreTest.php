<?php

namespace Tests\Feature\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Traits\Bigcommerce\CanUninstallStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CanUninstallStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the installation flag on a store is set on uninstallation
     *
     * @return void
     */
    public function testUninstallStore()
    {
        /** @var CanUninstallStore $trait */
        $trait = $this->getMockForTrait(CanUninstallStore::class);

        $store = BigcommerceStore::factory()->create();

        // Confirm store created in database
        $this->assertDatabaseHas('bigcommerce_stores', ['store_hash' => $store->store_hash]);

        Event::fake();
        
        // Uninstall store
        $trait->uninstallStore($store->store_hash);

        // Confirm installation flag set to false
        $this->assertDatabaseHas('bigcommerce_stores', ['store_hash' => $store->store_hash, 'installed' => false]);
    }
}
