<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait SendsJsonResponse
{
    protected function jsonResponse(?array $data, int $statusCode = 200, array $meta = []): JsonResponse
    {
        return response()->json(['data' => $data, 'meta' => $meta], $statusCode);
    }

    protected function jsonErrorResponse(string $errorMessage, int $statusCode = 400): JsonResponse
    {
        return response()->json(['error' => $errorMessage], $statusCode);
    }
}
