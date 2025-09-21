<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Repositories\Notification;

use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\UserDevice\UserDevice;
use App\Modules\Base\Application\Response\DataStatus;
use Exception;
use Carbon\Carbon;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Notification\Application\DTOS\Notification\NotificationDTO;
use App\Modules\Notification\Application\Enums\Notification\NotificationDurationTypeEnum;
use App\Modules\Notification\Infrastructure\Persistence\Models\Notification\Notification;
use App\Modules\Notification\Infrastructure\Persistence\ApiService\NotificationApiService;
use App\Modules\Notification\Infrastructure\Persistence\Models\Topic\Topic;

class NotificationRepository extends BaseRepositoryAbstract
{
    protected $notificationApiService;
    public function __construct()
    {
        $this->setModel(new Notification());
        $this->notificationApiService = new NotificationApiService();
    }

    public function SendNotification(NotificationDTO $notificationDTO): DataStatus
    {
        try {
            $tokens = null;
            $topic = null;
            // dd($notificationDTO);
            if (isset($notificationDTO->userIds) && count($notificationDTO->userIds) > 0) {
                // dd('token');
                $tokens =  UserDevice::whereIn('user_id', $notificationDTO->userIds)->pluck('device_token')->toArray();
            } else if (isset($notificationDTO->topic_id) && $notificationDTO->topic_id != null) {
                $topic = Topic::find($notificationDTO->topic_id)->name;
            }
            // dd($tokens, $topic);
            $response = $this->notificationApiService->sendNotification(notificationDTO: $notificationDTO, topic: $topic, tokens: $tokens);
            return DataSuccess(
                status: true,
                message: 'Notification sent successfully',
                data: $response
            );
        } catch (Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
