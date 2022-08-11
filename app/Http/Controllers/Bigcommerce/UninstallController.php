<?php

namespace App\Http\Controllers\Bigcommerce;

use App\Http\Controllers\Controller;
use App\Traits\Bigcommerce\CanReadSignedPayload;
use App\Traits\Bigcommerce\CanUninstallStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UninstallController extends Controller
{
    use CanReadSignedPayload, CanUninstallStore;

    /**
     * Endpoint called server-to-server by BigCommerce when app uninstalled
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $validator = $this->requestValidator($request->query());
        $validator->validate();

        $payload = $this->readPayload($request->query('signed_payload'), config('bigcommerce.client_secret'));
        $payloadValidator = $this->payloadValidator($payload);
        $payloadValidator->validate();

        $this->uninstallStore($payload['store_hash']);
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
}
