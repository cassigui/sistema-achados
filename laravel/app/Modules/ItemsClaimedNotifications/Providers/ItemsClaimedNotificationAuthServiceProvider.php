<?php

namespace App\Modules\ItemsClaimedNotifications\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class ItemsClaimedNotificationAuthServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        'App\Modules\ItemsClaimedNotifications\ItemsClaimedNotification' => 'App\Modules\ItemsClaimedNotifications\ItemsClaimedNotificationPolicy',
    ];
    
    public function register()
    {
        $this->registerPolicies();
    }
}
