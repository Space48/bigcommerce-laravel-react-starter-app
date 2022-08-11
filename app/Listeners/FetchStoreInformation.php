<?php

namespace App\Listeners;

use App\Events\StoreEvent;

class FetchStoreInformation
{
    /**
     * Retrieve store info from BigCommerce and update our local record.
     *
     * @throws \Exception;
     */
    public function handle(StoreEvent $event)
    {
        if (!$event->store) {
            return;
        }

        \App\Jobs\FetchStoreInformation::dispatch($event->store)->onQueue('high');
    }
}
