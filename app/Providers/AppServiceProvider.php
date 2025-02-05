<?php

namespace App\Providers;

use App\Services\ApiService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ApiService::class, function ($app) {
            return new ApiService(config('services.api.url'));
        });
    }
}