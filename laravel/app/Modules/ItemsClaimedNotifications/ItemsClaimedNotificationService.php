<?php
namespace App\Modules\ItemsClaimedNotifications;

use Illuminate\Support\Facades\DB;

class ItemsClaimedNotificationService
{
    /**
     * Retorna as notificações paginadas de um usuário (administrador).
     */
    public function getNotificationsForUser($user, int $perPage = 10)
    {
        info($user);

        if (! $user || ! $user->super_admin) {
            return collect();
        }

        return DB::table('notifications')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Marca uma notificação específica como lida.
     */
    public function markAsRead($user, string $notificationId): bool
    {
        if (! $user || (! $user->isAdmin() && ! $user->super_admin)) {
            return false;
        }

        $updated = DB::table('notifications')
            ->where('id', $notificationId)
            ->update(['read_at' => now()]);

        return $updated > 0;
    }
    /**
     * Remove uma notificação.
     */
    public function destroy($user, string $notificationId): bool
    {
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->delete();
            return true;
        }

        return false;
    }
}
