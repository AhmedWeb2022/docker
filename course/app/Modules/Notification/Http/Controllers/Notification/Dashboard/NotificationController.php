<?php

namespace App\Modules\Notification\Http\Controllers\Notification\Dashboard;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Notification\Application\UseCases\Notification\NotificationUseCase;
use App\Modules\Notification\Http\Requests\Notification\Global\NotificationIdRequest;
use App\Modules\Notification\Http\Requests\Notification\Dashboard\FetchNotificationRequest;
use App\Modules\Notification\Http\Requests\Notification\Dashboard\CreateNotificationRequest;
use App\Modules\Notification\Http\Requests\Notification\Dashboard\UpdateNotificationRequest;

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

    public function createNotification(CreateNotificationRequest $request)
    {
        return $this->NotificationUseCase->createNotification($request->toDTO())->response();
    }

    public function updateNotification(UpdateNotificationRequest $request)
    {
        return $this->NotificationUseCase->updateNotification($request->toDTO())->response();
    }

    public function deleteNotification(NotificationIdRequest $request)
    {
        return $this->NotificationUseCase->deleteNotification($request->toDTO())->response();
    }
}
