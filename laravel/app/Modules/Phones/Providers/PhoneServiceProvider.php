<?php

namespace App\Modules\Phones\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PhoneServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'phones');

        Route::middleware('web')
            ->prefix('sistema')
            ->namespace('App\Modules\Phones\Http\Controllers')
            ->group(__DIR__ . '/../routes/web.php');

        Route::middleware(['api', 'jwt.auth', 'jwt.refresh'])
            ->prefix('api')
            ->namespace('App\Modules\Phones\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->app->register(PhoneEventServiceProvider::class);
        $this->app->register(PhoneAuthServiceProvider::class);
    }
}
