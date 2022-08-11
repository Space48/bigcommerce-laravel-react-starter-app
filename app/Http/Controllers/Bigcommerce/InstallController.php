<?php

namespace App\Http\Controllers\Bigcommerce;

use App\Events\StoreInstalled;
use App\Http\Controllers\Controller;
use App\Services\Bigcommerce;
use App\Traits\Bigcommerce\CanInstallStore;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InstallController extends Controller
{
    use CanInstallStore;

    /**
     * BigCommerce service
     */
    protected Bigcommerce $bigcommerce;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Bigcommerce $bigcommerce)
    {
        $this->bigcommerce = $bigcommerce;
    }

    /**
     * Start BigCommerce installation process
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $validator = $this->validator($request->query());
        if ($validator->fails()) {
            abort(400, 'Failed to install. Missing required install parameters');
        }

        try {
            $token = $this->bigcommerce->requestPermanentOauthToken(
                $request->query('code'),
                $request->query('scope'),
                $request->query('context'),
                route('bc.install')
            );

            $tokenValidator = $this->tokenValidator($token);
            if ($tokenValidator->fails()) {
                abort(400, 'Failed to install. Unable to authenticate with BigCommerce');
            }

            $store = $this->installStore(
                $token['context'],
                $token['scope'],
                $token['access_token'],
                $token['user']['id'],
                $token['user']['email']
            );

            event(new StoreInstalled($store));

        } catch (Exception $e) {
            if ($request->has('external_install')) {
                return redirect('https://login.bigcommerce.com/app/' . config('bigcommerce.client_id') . '/install/failed');
            }

            Log::error($e->getMessage());
            abort(400, 'Unable to install');
        }

        if ($request->has('external_install')) {
            return redirect('https://login.bigcommerce.com/app/' . config('bigcommerce.client_id') . '/install/succeeded');
        }

        return redirect()->route('store.installed', ['store' => $store]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'code' => ['required', 'string', 'max:255'],
            'context' => ['required', 'string', 'max:255',],
            'scope' => [
                'required', 'string',
                function ($attribute, $value, $fail) {
                    $requiredScopes = ['store_v2_default'];
                    $scopes = explode(" ", $value);
                    foreach ($requiredScopes as $requiredScope) {
                        if (!in_array($requiredScope, $scopes)) {
                            $fail("Missing a required scope of: " . $requiredScope);
                        }
                    }
                },
            ],
        ]);
    }

    /**
     * Verify oauth token received from BigCommerce
     *
     * @param array $token
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function tokenValidator(array $token)
    {
        return Validator::make($token, [
            'access_token' => ['required', 'string', 'filled'],
            'scope' => ['required', 'string', 'filled'],
            'user.email' => ['required', 'string', 'filled'],
            'user.id' => ['required', 'integer', 'filled'],
            'context' => ['required', 'string', 'filled'],
        ]);
    }
}
