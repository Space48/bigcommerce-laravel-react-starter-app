<?php

namespace App\Listeners;

use App\Events\StoreEvent;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class SetupStore
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(StoreEvent $event)
    {
        // Ensure that we have the latest version of the store model
        $event->store->refresh();

        try {
            Bus::chain([
                new \App\Jobs\FetchStoreInformation($event->store),
                new \App\Jobs\SetupWebhooks($event->store),
            ])->onQueue('high')->dispatch();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
