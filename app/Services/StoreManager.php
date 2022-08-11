<?php

namespace App\Services;

use App\Models\BigcommerceStore;
use App\Services\Bigcommerce\Response\Exception;
use App\Traits\Bigcommerce\CanUninstallStore;

class StoreManager
{
    use CanUninstallStore;

    private Bigcommerce $bigcommerce;

    public function __construct(Bigcommerce $bigcommerce)
    {
        $this->bigcommerce = $bigcommerce;
    }

    /**
     * Fetch latest meta data about a store from BigCommerce
     *
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     */
    public function fetchAndUpdateInfo(BigcommerceStore $store): BigcommerceStore
    {
        if (!$store->installed) {
            return $store;
        }

        try {
            $storeInfo = $this->bigcommerce->fetchStoreInformation(
                $store->access_token,
                $store->store_hash
            );
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'Store ' . $store->store_hash . ' does not exist') ||
                str_contains($e->getMessage(), 'The store is suspended.') ||
                str_contains($e->getMessage(), 'You can not perform the requested method on an expired store.')) {
                $this->uninstallStore($store->store_hash);
                return $store;
            } else {
                throw $e;
            }
        }

        $store = $this->addStoreInfoToStore($store, $storeInfo);
        $store->save();

        return $store;
    }

    /**
     * Add meta information about store to store record.
     *
     * @param BigcommerceStore $store
     * @param array $storeInfo - Response from BC Store Info request.
     * @return BigcommerceStore
     */
    private function addStoreInfoToStore(BigcommerceStore $store, array $storeInfo): BigcommerceStore
    {
        $store->store_id = $storeInfo['store_id'] ?? null;
        $store->domain = $storeInfo['domain'] ?? null;
        $store->country = $storeInfo['country_code'] ?? null;
        $store->currency = $storeInfo['currency'] ?? null;
        $store->name = $storeInfo['name'] ?? null;
        $store->plan_is_trial = $storeInfo['plan_is_trial'] ?? null;
        $store->plan_level = $storeInfo['plan_level'] ?? null;
        $store->plan_name = $storeInfo['plan_name'] ?? null;
        $store->status = $storeInfo['status'] ?? null;
        $store->timezone_name = $storeInfo['timezone']['name'] ?? null;
        $store->timezone_raw_offset = $storeInfo['timezone']['raw_offset'] ?? null;
        $store->timezone_dst_offset = $storeInfo['timezone']['dst_offset'] ?? null;
        $store->timezone_dst_correction = $storeInfo['timezone']['dst_correction'] ?? null;

        return $store;
    }
}
