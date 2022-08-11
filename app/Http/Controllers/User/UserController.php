<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BigcommerceStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __invoke(Request $request, BigcommerceStore $store)
    {
        return $this->jsonResponse(Auth::user()->toArray());
    }
}
