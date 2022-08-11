<?php

namespace App\Http\Controllers;

use App\Traits\SendsJsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, SendsJsonResponse, ValidatesRequests;

    /**
     * @param array $messages
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function redirectToErrorPage(array $messages)
    {
        return redirect('/error')->withCookie(
            \cookie('last_error', implode('\n', $messages), 0, '/', null, true, false)
        );
    }
}
