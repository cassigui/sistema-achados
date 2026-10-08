<?php
namespace App\Modules\Items\Providers;

use App\Modules\Items\Providers\ItemEventServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ItemServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'items');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'items');

        Route::middleware('web')
            ->prefix('web')
            ->namespace('App\Modules\Items\Http\Controllers')
            ->group(__DIR__ . '/../routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\Modules\Items\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->app->register(ItemEventServiceProvider::class);
        $this->app->register(ItemAuthServiceProvider::class);
    }
}
