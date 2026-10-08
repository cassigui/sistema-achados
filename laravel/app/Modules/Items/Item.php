<?php

namespace App\Modules\Items;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{   
    protected $fillable = [
        'name',
        'item_id',
    ];

    protected $casts = [
        'checked' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo('App\Modules\Items\Item');
    }

    public function installation_item()
    {
        return $this->belongsToMany('App\Modules\InstallationItems\InstallationItem', 'item_installation_check')->withPivot('checked');
    }
}
