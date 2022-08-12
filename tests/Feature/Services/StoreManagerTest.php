<?php

namespace Tests\Feature\Services;

use App\Events\StoreUninstalled;
use App\Models\BigcommerceStore;
use App\Services\Bigcommerce;
use App\Services\Bigcommerce\Response\Exception;
use App\Services\StoreManager;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class StoreManagerTest extends TestCase
{
    private Bigcommerce $bigcommerce;

    public function setUp(): void
    {
        parent::setUp();

        $this->bigcommerce = $this->createMock(Bigcommerce::class);
    }

    public function testFetchAndUpdateInfo()
    {
        $storeInfo = [
            'store_id' => 1123,
            'domain' => 'https://test.mybigcommerce.com',
            'country_code' => 'GB',
            'currency' => 'GBP',
            'name' => 'Test Store',
            'plan_is_trial' => false,
            'plan_level' => 'Sandbox Store',
            'plan_name' => 'Partner Sandbox',
            'status' => 'prelaunch',
            'timezone' => [
                'name' => 'Europe/London',
                'raw_offset' => 0,
                'dst_offset' => 3600,
                'dst_correction' => true,
            ],
        ];

        $this->bigcommerce->expects($this->once())
            ->method('fetchStoreInformation')
            ->willReturn($storeInfo);

        $manager = new StoreManager($this->bigcommerce);

        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->make(['installed' => true]);
        $store = $manager->fetchAndUpdateInfo($store);

        $this->assertEquals($storeInfo['store_id'], $store->store_id);
        $this->assertEquals($storeInfo['domain'], $store->domain);
        $this->assertEquals($storeInfo['country_code'], $store->country);
        $this->assertEquals($storeInfo['currency'], $store->currency);
        $this->assertEquals($storeInfo['name'], $store->name);
        $this->assertEquals($storeInfo['plan_is_trial'], $store->plan_is_trial);
        $this->assertEquals($storeInfo['plan_level'], $store->plan_level);
        $this->assertEquals($storeInfo['plan_name'], $store->plan_name);
        $this->assertEquals($storeInfo['status'], $store->status);
        $this->assertEquals($storeInfo['timezone']['name'], $store->timezone_name);
        $this->assertEquals($storeInfo['timezone']['raw_offset'], $store->timezone_raw_offset);
        $this->assertEquals($storeInfo['timezone']['dst_offset'], $store->timezone_dst_offset);
        $this->assertEquals($storeInfo['timezone']['dst_correction'], $store->timezone_dst_correction);
    }

    public function testDontFetchAndUpdateInfoWhenNotInstalled()
    {
        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->make(['installed' => false]);
        $this->bigcommerce->expects($this->never())
            ->method('fetchStoreInformation');

        $manager = new StoreManager($this->bigcommerce);
        $manager->fetchAndUpdateInfo($store);
    }

    public function testUninstalledStoreWhenNoLongerExists()
    {
        Event::fake();

        /** @var BigcommerceStore $store */
        $store = BigcommerceStore::factory()->create();
        $this->bigcommerce->expects($this->once())
            ->method('fetchStoreInformation')
            ->willThrowException(new Exception(400, 'Store ' . $store->store_hash . ' does not exist'));

        $manager = new StoreManager($this->bigcommerce);
        $manager->fetchAndUpdateInfo($store);

        Event::assertDispatched(StoreUninstalled::class);
    }
}
