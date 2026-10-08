<?php
namespace App\Modules\Account\Users;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;
    use Authorizable;

    protected $casts = [
        'active'   => 'boolean',
        'super_admin' => 'boolean',
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'active',
        'super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'backup_codes',
    ];

    public function isAdmin(): bool
    {
        return (bool) $this->super_admin;
    }

    public function isOwnerOf($item): bool
    {
        return (int) $this->id === (int) $item->user_id;
    }

    public function getAuditTranslationPrefix(): string
    {
        return 'account::toasts.users';
    }

    public function authenticable()
    {
        return $this->morphTo();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
