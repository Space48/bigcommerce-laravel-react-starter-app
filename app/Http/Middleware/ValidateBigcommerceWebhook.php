<?php

namespace App\Http\Middleware;

use App\Traits\SendsJsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ValidateBigcommerceWebhook
{
    use SendsJsonResponse;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->input();

        try {
            $validator = $this->getWebhookDataValidator($input);
            $validator->validate();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->jsonErrorResponse('Malformed webhook');
        }

        return $next($request);
    }

    /**
     * Get a validator for an incoming load request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getWebhookDataValidator(array $data)
    {
        return Validator::make(
            $data,
            [
                'scope' => ['required', 'filled', 'string'],
                'store_id' => ['required', 'filled', 'string'],
                'data' => ['required', 'array'],
                'data.type' => ['required', 'filled', 'string'],
                'data.id' => ['required', 'filled', 'integer'],
                'hash' => ['required', 'filled', 'string'],
                'producer' => ['required', 'filled', 'string'],
            ]
        );
    }
}
