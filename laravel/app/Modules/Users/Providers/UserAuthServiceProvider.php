<?php

namespace App\Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class UserAuthServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        'App\Modules\Users\User' => 'App\Modules\Users\UserPolicy',
    ];
    
    public function register()
    {
        $this->registerPolicies();
    }
}
