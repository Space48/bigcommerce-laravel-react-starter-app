<?php

namespace App\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait CanRemoveStoreUser
{
    /**
     * Remove store access for user.
     * For now, we're not deleting user even if no other store access.
     *
     * @param integer $bigcommerceUserId
     * @param BigcommerceStore $store
     * @return User
     *
     * @throws ModelNotFoundException
     */
    public function removeStoreUser(
        int $bigcommerceUserId,
        BigcommerceStore $store
    ) {

        $user = User::where('bigcommerce_id', $bigcommerceUserId)->firstOrFail();

        $relationship = BigcommerceStoreUser::where([
            ['bigcommerce_store_id', '=', $store->id],
            [ 'user_id', '=', $user->id]
        ])->delete();

        return $user;
    }
}
