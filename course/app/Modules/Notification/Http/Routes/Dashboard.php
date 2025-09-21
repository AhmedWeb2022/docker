<?php


use Illuminate\Support\Facades\Route;
use App\Modules\Notification\Http\Controllers\Topic\Dashboard\TopicController;
use App\Modules\Notification\Http\Controllers\Notification\Dashboard\NotificationController;

Route::prefix('dashboard')->middleware('baseAuthMiddleware:employee')->group(function () {
    route::controller(NotificationController::class)->group(function () {
        Route::post('fetch_notifications', 'fetchNotifications');
        Route::post('create_notification', 'createNotification');
        Route::post('update_notification', 'updateNotification');
        Route::post('delete_notification', 'deleteNotification');
    });
    route::controller(TopicController::class)->group(function () {
        Route::post('fetch_topics', 'fetchTopics');
        Route::post('create_topic', 'createTopic');
        Route::post('update_topic', 'updateTopic');
        Route::post('delete_topic', 'deleteTopic');
    });
});
