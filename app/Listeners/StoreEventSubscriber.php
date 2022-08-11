<?php

namespace App\Listeners;

use App\Events\StoreInstalled;
use App\Events\StoreLoaded;
use App\Events\StoreUninstalled;
use Illuminate\Events\Dispatcher;

class StoreEventSubscriber
{
    /**
     * When the app is installed, loaded or uninstalled, carry out tasks.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
        $this->subscribeStoreInstalled($events);
        $this->subscribeStoreLoaded($events);
        $this->subscribeStoreUninstalled($events);
    }

    /**
     * @param Dispatcher $events
     */
    private function subscribeStoreInstalled($events)
    {
        // StoreInstalled Events
        $storeInstalledEvents = [
            SetupStore::class,
        ];

        foreach ($storeInstalledEvents as $storeInstalledEvent) {
            $events->listen(
                StoreInstalled::class,
                $storeInstalledEvent
            );
        }
    }

    /**
     * @param Dispatcher $events
     */
    private function subscribeStoreLoaded($events)
    {
        $storeLoadedEvents = [
            FetchStoreInformation::class,
            SetupWebhooks::class,
        ];

        foreach ($storeLoadedEvents as $storeLoadedEvent) {
            $events->listen(
                StoreLoaded::class,
                $storeLoadedEvent
            );
        }
    }

    /**
     * @param Dispatcher $events
     */
    private function subscribeStoreUninstalled($events)
    {
        $storeUninstalledEvents = [
        ];

        foreach ($storeUninstalledEvents as $storeUninstalledEvent) {
            $events->listen(
                StoreUninstalled::class,
                $storeUninstalledEvent
            );
        }
    }
}
