<?php

namespace App\Http\Middleware;

use App\Traits\SendsJsonResponse;
use Closure;
use Illuminate\Http\Request;

class AuthenticateBigcommerceWebhook
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
        if (is_null($request->header('secret'))) {
            return $this->jsonErrorResponse('Missing secret on webhook');
        }

        if ($request->header('secret') !== config('bigcommerce.webhook_secret')) {
            return $this->jsonErrorResponse('Invalid secret provided on webhook', 401);
        }

        return $next($request);
    }
}
