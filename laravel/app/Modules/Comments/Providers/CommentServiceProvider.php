<?php
namespace App\Modules\Comments\Providers;

use App\Modules\Comments\Providers\CommentEventServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'comments');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'comments');

        Route::middleware('web')
            ->prefix('web')
            ->namespace('App\Modules\Comments\Http\Controllers')
            ->group(__DIR__ . '/../routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\Modules\Comments\Http\Controllers')
            ->group(__DIR__ . '/../routes/api.php');
    }

    public function register()
    {
        $this->app->register(CommentEventServiceProvider::class);
        $this->app->register(CommentAuthServiceProvider::class);
    }
}
