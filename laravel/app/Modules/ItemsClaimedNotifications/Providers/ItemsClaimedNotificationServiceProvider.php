<?php
namespace App\Modules\ItemsClaimedNotifications\Providers;

use App\Modules\ItemsClaimedNotifications\Providers\ItemsClaimedNotificationEventServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ItemsClaimedNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'itemsClaimedNotifications');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'itemsClaimedNotifications');

        Route::middleware('web')
            ->prefix('web')
            ->namespace('App\Modules\ItemsClaimedNotifications\Http\Controllers')
            ->group(__DIR__ . '/../routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\Modules\ItemsClaimedNotifications\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->app->register(ItemsClaimedNotificationEventServiceProvider::class);
        $this->app->register(ItemsClaimedNotificationAuthServiceProvider::class);
    }
}
