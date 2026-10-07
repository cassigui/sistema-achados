<?php

namespace App\Modules\Configs;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key',
        'value',
    ];
}
