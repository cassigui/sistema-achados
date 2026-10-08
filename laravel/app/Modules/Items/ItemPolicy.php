<?php

namespace App\Modules\Items;

use Illuminate\Foundation\Auth\User;
use App\Modules\Base\BasePolicy;

class ItemPolicy extends BasePolicy
{
    public function view(User $user)
    {
        return $this->can($user, 'view', 'items');
    }

    public function create(User $user)
    {
        return $this->can($user, 'create', 'items');
    }

    public function update(User $user)
    {
        return $this->can($user, 'update', 'items');
    }

    public function delete(User $user)
    {
        return $this->can($user, 'delete', 'items');
    }

    public function restore(User $user)
    {
        return $this->can($user, 'restore', 'items');
    }
}
