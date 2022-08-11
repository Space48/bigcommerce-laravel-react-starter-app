<?php

namespace App\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait CanManageOwner
{

    /**
     * Ensures store ownership is kept updated where possible
     */
    public function updateOwner(
        int $bigcommerceUserId,
        BigcommerceStore $store,
    ) {
        $user = User::firstWhere('bigcommerce_id', '=', $bigcommerceUserId);
        if (!$user) {
            Log::info('Owner (BC ID: ' . $bigcommerceUserId . ') does not exist for store (ID: ' . $store->id . ')');
        }

        $store->store_users->load('user');

        /** @var BigcommerceStoreUser $store_user */
        foreach ($store->store_users as $store_user)
        {
            $isOwner = $store_user->user->bigcommerce_id == $bigcommerceUserId;
            if ($store_user->is_owner != $isOwner) {
                $store_user->is_owner = $isOwner;
                $store_user->save();
            }
        }
    }
}
