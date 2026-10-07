<?php

namespace App\Modules\Images;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'path',
        'imageable_id',
        'category',
        'imageable_type',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}
