<?php

namespace App\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait CanAddStoreUser
{

    /**
     * Finds or creates user from provided info and assigns to store.
     *
     * @param integer $bigcommerceUserId
     * @param string $email
     * @param BigcommerceStore $store
     * @param integer $bigcommerceOwnerId
     * @return User
     */
    public function addStoreUser(
        int $bigcommerceUserId,
        string $email,
        BigcommerceStore $store,
        int $bigcommerceOwnerId
    ) {
        $user = User::firstOrCreate(
            ['bigcommerce_id' => $bigcommerceUserId],
            ['email' => $email, 'password' => Hash::make(Str::random(20))]
        );

        $isOwner = $bigcommerceUserId == $bigcommerceOwnerId;
        BigcommerceStoreUser::firstOrCreate(
            ['user_id' => $user->id, 'bigcommerce_store_id' => $store->id],
            ['is_owner' => $isOwner]
        );

        return $user;
    }
}
