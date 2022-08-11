<?php

namespace App\Http\Controllers\Bigcommerce;

use App\Http\Controllers\Controller;
use App\Models\BigcommerceStore;
use App\Services\Bigcommerce;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProxyController extends Controller
{

    public function __construct(protected Bigcommerce $bigcommerce)
    {
    }

    public function __invoke(Request $request, BigcommerceStore $store, string $endpoint): Response
    {
        $response = $this->bigcommerce->rawRequest(
            $store->access_token,
            $request->getMethod(),
            $store->store_hash,
            $endpoint,
            $request->query(),
            $request->json()->all()
        );

        return response($response->body(), $response->status(), $response->headers());
    }
}
