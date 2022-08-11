<?php

namespace App\Events;

use App\Models\BigcommerceStore;
use Illuminate\Queue\SerializesModels;

class StoreEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public BigcommerceStore $store)
    {
    }
}
