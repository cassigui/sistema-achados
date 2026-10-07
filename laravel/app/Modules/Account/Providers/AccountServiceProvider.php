<?php

namespace App\Modules\Account\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public function boot()
    {   
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'account');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'account');

        Route::middleware('web')
            ->prefix('web')
            ->group(__DIR__ . '/../routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__ . '/../routes/api.php');

        Relation::morphMap([
            'users'            => 'App\Modules\Account\Users\User',
            'access_levels'    => 'App\Modules\Account\Permissions\AccessLevels\AccessLevel',
            'user_permissions' => 'App\Modules\Account\Permissions\UserPermissions\UserPermission',
        ]);
    }

    public function register()
    {
        $this->app->register(AccountEventServiceProvider::class);
        $this->app->register(AccountAuthServiceProvider::class);
    }
}
