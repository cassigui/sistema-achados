<?php

namespace App\Modules\Comments\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class CommentAuthServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        'App\Modules\Comments\Comment' => 'App\Modules\Comments\CommentPolicy',
    ];
    
    public function register()
    {
        $this->registerPolicies();
    }
}
