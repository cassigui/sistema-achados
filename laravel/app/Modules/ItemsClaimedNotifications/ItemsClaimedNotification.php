<?php
namespace App\Modules\ItemsClaimedNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ItemsClaimedNotification extends Notification
{
    use Queueable;

    protected $item;
    protected $user;

    public function __construct($item = null, $user = null)
    {
        $this->item = $item;
        $this->user = $user;
    }
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'item_id'   => $this->item->id,
            'item_name' => $this->item->name ?? 'Item achado',
            'user_id'   => $this->user->id,
            'user_name' => $this->user->name,
            'message'   => "O usuário {$this->user->name} reivindicou a posse do item #{$this->item->id}.",
        ];
    }
}
