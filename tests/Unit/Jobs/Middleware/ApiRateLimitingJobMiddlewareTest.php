<?php

namespace Tests\Unit\Jobs\Middleware;

use App\Jobs\Middleware\ApiRateLimitingJobMiddleware;
use App\Models\BigcommerceStore;
use App\Services\Bigcommerce\Response\TooManyRequestsException;
use Illuminate\Queue\Jobs\Job;
use Tests\TestCase;

class ApiRateLimitingJobMiddlewareTest extends TestCase
{
    /**
     * When API throws an API limit exception, second job will not be processed within timeout period
     */
    public function testApiLimitBlocksProcessing()
    {
        $middleware = new ApiRateLimitingJobMiddleware($this->getStore());

        $job = $this->createMock(Job::class);

        $middleware->handle($job, function () {
            throw new TooManyRequestsException(5, 'Too many requests');
        });

        $middleware->handle($job, function () {
            $this->fail('Should not run again');
        });

        $this->assertTrue(true);
    }

    public function testApiLimitOnlyBlocksAffectedStore()
    {
        $middleware1 = new ApiRateLimitingJobMiddleware($this->getStore());
        $middleware2 = new ApiRateLimitingJobMiddleware($this->getStore());

        $job1 = $this->getMockBuilder(\stdClass::class)->addMethods(['handle', 'release'])->getMock();
        $job1->expects($this->exactly(1))
            ->method('handle')
            ->willThrowException(new TooManyRequestsException(5, 'Too many requests'));

        $job2 = $this->getMockBuilder(\stdClass::class)->addMethods(['handle'])->getMock();
        $job2->expects($this->exactly(1))->method('handle');

        $callable = function ($job) {
            $job->handle();
        };
        $middleware1->handle($job1, $callable);
        $middleware2->handle($job2, $callable);
    }

    /**
     * Once API limit delay period expires, the job can be retried again.
     *
     * @throws \Exception
     */
    public function testApiLimitBlockExpiresAfterDelay()
    {
        $middleware = new ApiRateLimitingJobMiddleware($this->getStore());

        $job1 = $this->getMockBuilder(\stdClass::class)->addMethods(['handle', 'release'])->getMock();
        $job1->expects($this->exactly(1))
            ->method('handle')
            ->willThrowException(new TooManyRequestsException(1, 'Too many requests'));

        $callable = function ($job) {
            $job->handle();
        };

        $middleware->handle($job1, $callable);

        sleep(1);

        $job2 = $this->getMockBuilder(\stdClass::class)->addMethods(['handle'])->getMock();
        $job2->expects($this->exactly(1))->method('handle');

        $middleware->handle($job2, $callable);

    }

    protected function getStore(): BigcommerceStore
    {
        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->make();
        return $store;
    }
}
