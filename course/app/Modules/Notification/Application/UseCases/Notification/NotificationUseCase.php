<?php

namespace App\Modules\Notification\Application\UseCases\Notification;


use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Notification\Application\DTOS\Notification\NotificationDTO;
use App\Modules\Notification\Http\Resources\Notification\NotificationResource;
use App\Modules\Notification\Application\DTOS\Notification\NotificationFilterDTO;
use App\Modules\Notification\Application\DTOS\NotificationUser\NotificationUserDTO;
use App\Modules\Notification\Infrastructure\Persistence\Models\Notification\Notification;
use App\Modules\Notification\Infrastructure\Persistence\Repositories\Notification\NotificationRepository;
use App\Modules\Notification\Infrastructure\Persistence\Repositories\NotificationUser\NotificationUserRepository;

class NotificationUseCase
{

    protected $NotificationRepository;
    protected $NotificationUserRepository;
    protected $user;
    protected $employee;
    public function __construct(NotificationRepository $NotificationRepository)
    {
        $this->NotificationRepository = $NotificationRepository;
        $this->NotificationUserRepository = new NotificationUserRepository();
        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }


    public function fetchNotifications(NotificationFilterDTO $NotificationFilterDTO): DataStatus
    {
        try {
            // dd($NotificationFilterDTO);
            $relations = [];
            if ($this->user) {
                $relations = ['users' => function ($query) {
                    $query->where('users.id', $this->user->id);
                }];
            }
            $NotificationFilterDTO->direction = 'desc';
            $Notifications = $this->NotificationRepository->filter(
                $NotificationFilterDTO,
                paginate: $NotificationFilterDTO->paginate,
                limit: $NotificationFilterDTO->limit,
                whereHasRelations: $relations
            );

            // $resourceData["unread_notifications_count"] = $this->user->notifications()->count();
            $resourceData = $NotificationFilterDTO->paginate ? NotificationResource::collection($Notifications)->response()->getData(true) : NotificationResource::collection($Notifications);
            return DataSuccess(
                status: true,
                message: 'Notification fetched successfully',
                resourceData: $resourceData,
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchUnreadNotificationCount(NotificationFilterDTO $NotificationFilterDTO): DataStatus
    {
        try {
            /* if (!$NotificationFilterDTO->user_id && $this->user) {
                $NotificationFilterDTO->user_id = $this->user->id;
            } */
            //    dd($NotificationFilterDTO);
            $unreadNotificationsCount = $this->NotificationRepository->filter(
                $NotificationFilterDTO,
                paginate: $NotificationFilterDTO->paginate,
                limit: $NotificationFilterDTO->limit,
                whereHasRelations: [
                    'users' => [
                        'users.id' => $this->user->id,
                        'is_read' => false,
                    ],
                ]
            )->count();
            return DataSuccess(
                status: true,
                message: 'Notification fetched successfully',
                resourceData: $unreadNotificationsCount,
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createNotification(NotificationDTO $NotificationDTO): DataStatus
    {
        try {
            // dd($NotificationDTO);
            try {
                $response = $this->NotificationRepository->SendNotification($NotificationDTO);
                if ($response->getStatus() == false) {
                    return $response;
                }
            } catch (\Exception $e) {
                return DataFailed(
                    status: false,
                    message: $e->getMessage()
                );
            }
            DB::beginTransaction();

            $notification = $this->NotificationRepository->create($NotificationDTO);

            if (isset($NotificationDTO->userIds) && count($NotificationDTO->userIds) > 0) {
                // dd($NotificationDTO->userIds);
                foreach ($NotificationDTO->userIds as $userId) {
                    $NotificationUserDTO = NotificationUserDTO::fromArray([
                        'notification_id' => $notification->id,
                        'user_id' => $userId,
                    ]);

                    $this->NotificationUserRepository->create($NotificationUserDTO);
                }
            }

            if (isset($NotificationDTO->topic_id) && $NotificationDTO->topic_id != null) {
                $NotificationUserDTO = NotificationUserDTO::fromArray([
                    'notification_id' => $notification->id,
                    'topic_id' => $NotificationDTO->topic_id,
                ]);

                $this->NotificationUserRepository->create($NotificationUserDTO);
            }

            DB::commit();

            return DataSuccess(
                status: true,
                message: 'Notification created successfully.',
                data: []
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return DataFailed(
                status: false,
                message: 'Notification creation failed: ' . $e->getMessage()
            );
        }
    }

    public function updateNotification(NotificationDTO $NotificationDTO): DataStatus
    {
        try {
            // dd($NotificationDTO);
            if (isset($NotificationDTO->notification_id)) {
                // $Notification = $this->NotificationRepository->update($NotificationDTO->notification_id, $NotificationDTO);
                $notification = $this->NotificationRepository->getFirst($NotificationDTO->notification_id);
                $notification->users()->where('users.id', $this->user->id)->update(['is_read' => true]);
                $notification->refresh();
                $notificationResource = new NotificationResource($notification);
            } else {
                //update all notifications for this user
                $unreadNotifications = $this->NotificationRepository->filter(
                    $NotificationDTO,
                    paginate: $NotificationDTO->paginate,
                    limit: $NotificationDTO->limit,
                    whereHasRelations: [
                        'users' => [
                            'users.id' => $this->user->id,
                            'is_read' => false,
                        ],
                    ]
                );
                // dd($unreadNotifications);
                if ($unreadNotifications->count() > 0) {
                    foreach ($unreadNotifications as $notification) {
                        /** @var Notification $notification */
                        $notification->users()->where('users.id', $this->user->id)->update(['is_read' => true]);

                        /*  $this->NotificationRepository->setModel($notification)->updatePivot(
                            relationMethod: 'users',
                            relatedId: $notification->id,
                            data: [
                                'is_read' => false
                            ]
                        ); */
                        /* $this->NotificationRepository
                            ->setModel($notification)
                            ->updatePivot('users', 1, ['is_read' => false]); */
                    }
                }
                $notificationResource = null;
            }

            return DataSuccess(
                status: true,
                message: ' Notification updated successfully',
                data: $notificationResource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteNotification(NotificationFilterDTO $NotificationFilterDTO): DataStatus
    {
        try {
            $Notification = $this->NotificationRepository->delete($NotificationFilterDTO->notification_id);
            return DataSuccess(
                status: true,
                message: ' Notification deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
