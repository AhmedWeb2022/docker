<?php

namespace App\Modules\Notification\Http\Controllers\Notification\Api;


use App\Http\Controllers\Controller;
use App\Modules\Notification\Application\UseCases\Notification\NotificationUseCase;
use App\Modules\Notification\Http\Requests\Notification\Global\NotificationIdRequest;
use App\Modules\Notification\Http\Requests\Notification\Api\CreateNotificationRequest;
use App\Modules\Notification\Http\Requests\Notification\Api\UpdateNotificationRequest;
use App\Modules\Notification\Http\Requests\Notification\Api\FetchNotificationRequest;

class NotificationController extends Controller
{
    protected $NotificationUseCase;

    public function __construct(NotificationUseCase $NotificationUseCase)
    {
        $this->NotificationUseCase = $NotificationUseCase;
    }

    public function fetchNotifications(FetchNotificationRequest $request)
    {
        return $this->NotificationUseCase->fetchNotifications($request->toDTO())->response();
    }
    
    public function fetchUnreadNotificationCount(FetchNotificationRequest $request)
    {
        return $this->NotificationUseCase->fetchUnreadNotificationCount($request->toDTO())->response();
    }

    public function updateNotification(UpdateNotificationRequest $request)
    {
        return $this->NotificationUseCase->updateNotification($request->toDTO())->response();
    }

}
