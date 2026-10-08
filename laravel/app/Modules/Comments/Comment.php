<?php
namespace App\Modules\Comments;

use App\Modules\Account\Users\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'content',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
