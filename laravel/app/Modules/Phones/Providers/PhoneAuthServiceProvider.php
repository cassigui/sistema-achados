<?php

namespace App\Modules\Phones\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class PhoneAuthServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        'App\Modules\Phones\Phone' => 'App\Modules\Phones\PhonePolicy',
    ];
    
    public function register()
    {
        $this->registerPolicies();
    }
}
