<?php

namespace App\Modules\Phones;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'number',
        'name',
        'comment',
        'phoneable_id',
        'phoneable_type',
    ];

    public function phoneable()
    {
        return $this->morphTo();
    }

}
