<?php

namespace App\Http\Controllers\Bigcommerce\Webhooks;

use App\Traits\Bigcommerce\CanUninstallStore;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppUninstalledController extends WebhookBase
{
    use CanUninstallStore;

    /**
     * Endpoint called server-to-server by BigCommerce when a client store is cancelled
     * and uninstalled from the platform
     *
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        $storeHash = $this->getStoreHash($request);

        try {
            $this->uninstallStore($storeHash);
        } catch (ModelNotFoundException $e) {
            Log::error('Store with store_hash ' . $storeHash . ' not found');
        }

        return response()->json();
    }
}
