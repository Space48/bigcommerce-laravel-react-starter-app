<?php

namespace App\Http\Controllers\Bigcommerce;

use App\Events\StoreLoaded;
use App\Http\Controllers\Controller;
use App\Models\BigcommerceStore;
use App\Traits\Bigcommerce\CanAddStoreUser;
use App\Traits\Bigcommerce\CanManageOwner;
use App\Traits\Bigcommerce\CanReadSignedPayload;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use UnexpectedValueException;

class LoadController extends Controller
{
    use CanReadSignedPayload, CanAddStoreUser, CanManageOwner;

    public function __invoke(Request $request)
    {
        $validator = $this->requestValidator($request->query());
        if ($validator->fails()) {
            return $this->redirectToErrorPage($validator->errors()->all());
        }

        try {
            $payload = $this->readPayload($request->query('signed_payload'), config('bigcommerce.client_secret'));
            $payloadValidator = $this->payloadValidator($payload);
            if ($payloadValidator->fails()) {
                return $this->redirectToErrorPage($validator->errors()->all());
            }

            /** @var BigcommerceStore $store */
            $store = $this->getStoreFromHash($payload['store_hash']);

            $user = $this->addStoreUser(
                $payload['user']['id'],
                $payload['user']['email'],
                $store,
                $payload['owner']['id']
            );
            $this->updateOwner($payload['owner']['id'], $store);

            $this->logUserInIfRequired($user);

        } catch (Exception $e) {
            return $this->redirectToErrorPage([$e->getMessage()]);
        }

        event(new StoreLoaded($store));

        // Redirect new users to welcome splash screen
        /** @var CarbonInterface $createdAt */
        $createdAt = $user->created_at;
        if ($createdAt->greaterThanOrEqualTo(Carbon::now()->subMinute())) {
            return redirect()->route('store.welcome', ['store' => $store, 'signed_payload' => $request->query('signed_payload')]);
        }

        return redirect()->route('store.home', ['store' => $store, 'signed_payload' => $request->query('signed_payload')]);
    }

    /**
     * Get a validator for an incoming load request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function requestValidator(array $data)
    {
        return Validator::make($data, ['signed_payload' => ['required', 'filled', 'string']]);
    }

    /**
     * Validate payload contains required data from BigCommerce
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function payloadValidator(array $payload)
    {
        return Validator::make(
            $payload,
            [
                'user' => ['required', 'array'],
                'user.id' => ['required', 'filled', 'integer'],
                'user.email' => ['required', 'filled', 'string'],
                'owner' => ['required', 'array'],
                'owner.id' => ['required', 'filled', 'integer'],
                'store_hash' => ['required', 'filled', 'string'],
            ]
        );
    }

    /**
     * Get BigCommerce Store from store hash
     *
     * @param string storeHash
     * @return BigCommerceStore
     * @throws UnexpectedValueException
     */
    protected function getStoreFromHash(string $storeHash)
    {
        try {
            return BigcommerceStore::withStoreHash($storeHash)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            Log::error(sprintf('Tried to load store with hash \'%s\' but it doesn\'t exist', $storeHash));
            throw new UnexpectedValueException(
                'Unfortunately, your store was not recognised. Please re-install our application'
            );
        }
    }

    /**
     * Log the user in if they're not already logged in, or logged in
     * as someone else.
     *
     * @param Authenticatable $user
     * @return void
     */
    protected function logUserInIfRequired(Authenticatable $user)
    {
        if (!Auth::check() || Auth::user()->id !== $user->id) {
            Auth::login($user);
        }
    }
}
