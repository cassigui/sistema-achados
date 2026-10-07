<?php

namespace App\Modules\Phones\Providers;

use App\Modules\Phones\Phone;
use App\Modules\Phones\PhoneObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class PhoneEventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        Phone::observe(PhoneObserver::class);
    }

}
