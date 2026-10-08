<?php

use App\Modules\ItemsClaimedNotifications\Http\Controllers\ItemsClaimedNotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/admin/notifications', [ItemsClaimedNotificationController::class, 'index'])->name('admin.notifications');
    Route::post('/admin/notifications/{id}/read', [ItemsClaimedNotificationController::class, 'markAsRead'])->name('admin.notifications.read');
});
