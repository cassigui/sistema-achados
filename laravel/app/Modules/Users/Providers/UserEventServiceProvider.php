<?php

namespace App\Modules\Users\Providers;

use App\Modules\Users\User;
use App\Modules\Users\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class UserEventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        User::observe(UserObserver::class);
    }

}
