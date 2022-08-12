<?php

namespace App\Http\Controllers\Bigcommerce;

use App\Http\Controllers\Controller;
use App\Models\BigcommerceStore;
use App\Services\Bigcommerce;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProxyController extends Controller
{

    public function __construct(protected Bigcommerce $bigcommerce)
    {
    }

    public function __invoke(Request $request, BigcommerceStore $store, string $endpoint): JsonResponse
    {
        $response = $this->bigcommerce->rawRequest(
            $store->access_token,
            $request->getMethod(),
            $store->store_hash,
            $endpoint,
            $request->query(),
            $request->json()->all()
        );

        return $this->jsonResponse(json_decode($response->body(), true), $response->status());
    }
}
