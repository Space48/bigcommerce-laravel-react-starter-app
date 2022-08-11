<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ManagesApiLimitFlag
{
    /**
     * When hitting a rate limit, set a retry time in the cache.
     * Expires from cache upon retry time
     */
    public function setApiRetryAfter(string $storeHash, int $seconds): void
    {
        Cache::put(
            $this->getApiLimitCacheKey($storeHash),
            now()->addSeconds($seconds)->timestamp,
            $seconds
        );
    }

    /**
     * If API requests are rate limited, provide delay in seconds when they can be restarted
     */
    public function getApiRetryAfter(string $storeHash): ?int
    {
        $timestamp = Cache::get($this->getApiLimitCacheKey($storeHash));
        if (!$timestamp) {
            return null;
        }

        return $timestamp - time();
    }

    protected function getApiLimitCacheKey(string $storeHash): string
    {
        return 'bigcommerce-api-retry-after-' . $storeHash;
    }
}
