<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Notification\Http\Controllers\Notification\Api\NotificationController;

Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['baseAuthMiddleware:user']], function () {
        Route::controller(NotificationController::class)->group(function () {
            Route::post('fetch_notifications', 'fetchNotifications');
            Route::post('fetch_unread_notification_count', 'fetchUnreadNotificationCount');
            Route::post('read_notification', 'updateNotification');
        });
    });
});
