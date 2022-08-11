<?php

namespace App\Http\Controllers\Bigcommerce\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\BigcommerceStore;
use Illuminate\Http\Request;

class WebhookBase extends Controller
{

    public function __construct()
    {
        $this->middleware('bigcommerce.webhook.auth');
        $this->middleware('bigcommerce.webhook.validate');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getStoreHash(Request $request): string
    {
        $producer = $request->input('producer');
        return BigcommerceStore::getStoreHashFromContext($producer);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDataId(Request $request)
    {
        return $request->input('data.id');
    }

    public function getDataType(Request $request)
    {
        return $request->input('data.type');
    }
}
