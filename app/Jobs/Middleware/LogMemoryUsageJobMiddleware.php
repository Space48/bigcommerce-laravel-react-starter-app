<?php

namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Log;

class LogMemoryUsageJobMiddleware
{
    public function handle($job, $next)
    {
        try {
            $next($job);
        } finally {
            Log::info('Memory usage for ' . get_class($job) . ': ' . ceil(memory_get_peak_usage() / 1024 / 1024) . 'M of ' . ini_get('memory_limit'));
        }
    }
}
