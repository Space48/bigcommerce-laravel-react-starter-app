<?php

namespace App\Traits\Bigcommerce;

use App\Events\StoreUninstalled;
use App\Models\BigcommerceStore;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait CanUninstallStore
{
    /**
     * Mark store as uninstalled
     *
     * @param string $storeHash
     * @return BigcommerceStore
     *
     * @throws ModelNotFoundException
     */
    public function uninstallStore(string $storeHash)
    {
        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::withStoreHash($storeHash)->firstOrFail();
        $store->update(['installed' => false]);
        event(new StoreUninstalled($store));
        return $store;
    }
}
