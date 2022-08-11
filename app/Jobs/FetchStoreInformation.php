<?php

namespace App\Jobs;

use App\Jobs\Middleware\ApiRateLimitingJobMiddleware;
use App\Jobs\Middleware\LogMemoryUsageJobMiddleware;
use App\Models\BigcommerceStore;
use App\Services\StoreManager;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class FetchStoreInformation extends BigcommerceJob implements ShouldBeUnique
{
    public $uniqueFor = 3600;

    protected BigcommerceStore $store;

    public function __construct(BigcommerceStore $store)
    {
        $this->store = $store;
    }

    /**
     * Retrieve store info from BigCommerce and update our local record.
     *
     * @throws \Exception
     */
    public function handle(StoreManager $storeManager)
    {
        $storeManager->fetchAndUpdateInfo($this->store);
    }

    /**
     * Only allow one job to be dispatched at a time for each store.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->store->id;
    }

    public function middleware()
    {
        return [
            new ApiRateLimitingJobMiddleware($this->store),
            new LogMemoryUsageJobMiddleware(),
        ];
    }
}
