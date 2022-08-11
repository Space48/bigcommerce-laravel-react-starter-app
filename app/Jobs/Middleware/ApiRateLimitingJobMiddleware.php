<?php

namespace App\Jobs\Middleware;

use App\Models\BigcommerceStore;
use App\Services\Bigcommerce\Response\TooManyRequestsException;
use App\Traits\ManagesApiLimitFlag;
use Illuminate\Support\Facades\Log;

class ApiRateLimitingJobMiddleware
{
    use ManagesApiLimitFlag;

    public function __construct(public BigcommerceStore $store)
    {
    }

    public function handle($job, $next)
    {
        if ($retryAfter = $this->getApiRetryAfter($this->store->store_hash)) {
            $job->release($retryAfter);
            return;
        }

        try {
            $next($job);
        } catch (TooManyRequestsException $e) {
            Log::error($e->getMessage());

            $this->setApiRetryAfter($this->store->store_hash, $e->getRetryAfter());
            $job->release($e->getRetryAfter());
        }
    }

}
