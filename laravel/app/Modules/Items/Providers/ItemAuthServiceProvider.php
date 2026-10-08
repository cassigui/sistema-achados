<?php

namespace App\Modules\Items\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class ItemAuthServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        'App\Modules\Items\Item' => 'App\Modules\Items\ItemPolicy',
    ];
    
    public function register()
    {
        $this->registerPolicies();
    }
}
