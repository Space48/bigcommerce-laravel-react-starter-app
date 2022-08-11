<?php

namespace App\Jobs;

use App\Traits\ManagesApiLimitFlag;

/**
 * A series of sensible defaults for jobs that communicate with BigCommerce;
 *
 * Class BigcommerceJob
 * @package App\Jobs
 */
abstract class BigcommerceJob extends AbstractJob
{
    use ManagesApiLimitFlag;

    protected $maxExceptions = 3;

    public function retryUntil()
    {
        return now()->addHours(2);
    }

    /**
     * Fibinacci backoff strategy
     */
    public function backoff(): array
    {
        $fibs = [1, 1, 2, 3, 5, 8, 13, 21, 34, 55];

        $backoff = array_map(fn($i) => $i * 60, $fibs);

        return $backoff;
    }
}
