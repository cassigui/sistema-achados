<?php

namespace App\Modules\Comments;

use Illuminate\Foundation\Auth\User;
use App\Modules\Base\BasePolicy;

class CommentPolicy extends BasePolicy
{
    public function view(User $user)
    {
        return $this->can($user, 'view', 'comments');
    }

    public function create(User $user)
    {
        return $this->can($user, 'create', 'comments');
    }

    public function update(User $user)
    {
        return $this->can($user, 'update', 'comments');
    }

    public function delete(User $user)
    {
        return $this->can($user, 'delete', 'comments');
    }

    public function restore(User $user)
    {
        return $this->can($user, 'restore', 'comments');
    }
}
