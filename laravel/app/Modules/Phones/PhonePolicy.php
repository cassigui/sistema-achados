<?php

namespace App\Modules\Phones;

use Illuminate\Foundation\Auth\User;
use App\Modules\Base\BasePolicy;

class PhonePolicy extends BasePolicy
{
    public function view(User $user)
    {
        return $this->can($user, 'view', 'phones');
    }

    public function create(User $user)
    {
        return $this->can($user, 'create', 'phones');
    }

    public function update(User $user)
    {
        return $this->can($user, 'update', 'phones');
    }

    public function delete(User $user)
    {
        return $this->can($user, 'delete', 'phones');
    }

    public function restore(User $user)
    {
        return $this->can($user, 'restore', 'phones');
    }
}
