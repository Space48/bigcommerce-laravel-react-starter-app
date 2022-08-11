<?php

namespace App\Traits\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Models\BigcommerceStoreUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait CanInstallStore
{

    /**
     * Add new store
     *
     * @param string $context
     * @param string $scope
     * @param string $accessToken
     * @param int $ownerId
     * @param string $ownerEmail
     * @return BigcommerceStore
     */
    public function installStore(
        string $context,
        string $scope,
        string $accessToken,
        int $ownerId,
        string $ownerEmail
    ) {

        // Create or update store
        $store = BigcommerceStore::updateOrCreate(
            ['store_hash' => $this->getStoreHashFromContext($context)],
            ['scope' => $scope, 'access_token' => $accessToken, 'installed' => true]
        );

        // Create or update user.
        $user = User::updateOrCreate(
            ['bigcommerce_id' => $ownerId],
            ['email' => $ownerEmail, 'password' => Hash::make(Str::random(20))]
        );

        // Create user to store relationship
        BigcommerceStoreUser::updateOrCreate(
            ['user_id' => $user->id, 'bigcommerce_store_id' => $store->id],
            ['is_owner' => true]
        );

        // Log customer in.
        Auth::login($user);

        return $store;
    }

    /**
     * Strip out prefix to store hash in context string
     *
     * @param string $context
     * @return string
     */
    protected function getStoreHashFromContext(string $context)
    {
        return str_replace('stores/', '', $context);
    }
}
