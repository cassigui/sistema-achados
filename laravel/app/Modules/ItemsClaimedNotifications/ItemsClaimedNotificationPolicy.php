<?php

namespace App\Modules\ItemsClaimedNotifications;

use Illuminate\Foundation\Auth\User;
use App\Modules\Base\BasePolicy;

class ItemsClaimedNotificationPolicy extends BasePolicy
{
    public function view(User $user)
    {
        return $this->can($user, 'view', 'itemsClaimedNotifications');
    }

    public function create(User $user)
    {
        return $this->can($user, 'create', 'itemsClaimedNotifications');
    }

    public function update(User $user)
    {
        return $this->can($user, 'update', 'itemsClaimedNotifications');
    }

    public function delete(User $user)
    {
        return $this->can($user, 'delete', 'itemsClaimedNotifications');
    }

    public function restore(User $user)
    {
        return $this->can($user, 'restore', 'itemsClaimedNotifications');
    }
}
