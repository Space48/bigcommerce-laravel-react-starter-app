<?php

use App\Http\Controllers\Bigcommerce\RemoveUserController;
use App\Http\Controllers\Bigcommerce\UninstallController;
use App\Http\Controllers\Bigcommerce\Webhooks\AppUninstalledController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/bc/remove-user', RemoveUserController::class);
Route::get('/bc/uninstall', UninstallController::class);

Route::post('/bc/webhook/app/uninstalled', AppUninstalledController::class)
    ->name('webhook.app.uninstalled');