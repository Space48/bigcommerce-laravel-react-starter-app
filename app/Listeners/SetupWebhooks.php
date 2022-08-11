<?php

namespace App\Listeners;

use App\Events\StoreEvent;

class SetupWebhooks
{

    /**
     * Store has been installed or app loaded in the admin.
     * Perform a check of whether webhooks need to be created or updated.
     *
     * @param StoreEvent $event
     *
     * @throws \Exception
     */
    public function handle(StoreEvent $event)
    {
        if (!$event->store) {
            return;
        }

        \App\Jobs\SetupWebhooks::dispatch($event->store)->onQueue('high');
    }
}
