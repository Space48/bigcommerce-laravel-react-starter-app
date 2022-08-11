<?php

namespace App\Http\Controllers;

use App\Models\BigcommerceStore;

class AppController extends Controller
{
    public function __invoke(BigcommerceStore $store)
    {
        return view('app');
    }
}
