<?php

namespace App\Providers;

use App\Services\Bigcommerce;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Bigcommerce::class, function () {
            return new Bigcommerce(
                config('bigcommerce.client_id'),
                config('bigcommerce.client_secret')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceScheme('https');
    }
}
