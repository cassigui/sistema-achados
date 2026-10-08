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
        'active'            => 'boolean',
        'backup_codes'      => 'array',
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'access_level_id',
        'active',
        'password',
        'authenticable_type',
        'authenticable_id',

    ];

    protected $hidden = [
        'password',
        'remember_token',
        'super_admin',
        'backup_codes',
    ];

    public function access_level()
    {
        return $this->belongsTo('App\Modules\Account\Permissions\AccessLevels\AccessLevel');
    }

    public function permissions()
    {
        if (!$this->access_level()->exists()) {
            return [];
        }

        return $this->access_level->permissions()->orderBy('name', 'asc');
    }

    public function getAuditTranslationPrefix(): string
    {
        return 'account::toasts.users';
    }

    public function authenticable()
    {
        return $this->morphTo();
    }

    /**
     * Imagens do usuário (polimórficas). A foto de perfil usa category 'avatar'.
     */
    public function images()
    {
        return $this->morphMany('App\Modules\Images\Image', 'imageable');
    }
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
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
