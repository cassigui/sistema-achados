<?php
namespace App\Modules\ItemsClaimedNotifications\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ItemsClaimedNotifications\ItemsClaimedNotificationService;

class ItemsClaimedNotificationController extends Controller
{
    protected $notificationService;

    public function __construct(ItemsClaimedNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user          = auth()->user();
        $notifications = $this->notificationService->getNotificationsForUser($user);

        info($notifications);

        return view('itemsClaimedNotifications::admin.notifications.index', compact('notifications'));
    }
    public function markAsRead($id)
    {
        $user = auth()->user();
        $this->notificationService->markAsRead($user, $id);

        return back()->with('status', 'Notificação marcada como lida.');
    }
}
