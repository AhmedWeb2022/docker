<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Repositories\Topic;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Notification\Application\DTOS\Topic\TopicDTO;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Notification\Infrastructure\Persistence\Models\Topic\Topic;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\UserDevice\UserDevice;
use App\Modules\Notification\Infrastructure\Persistence\ApiService\NotificationApiService;


class TopicRepository extends BaseRepositoryAbstract
{
    protected $notificationApiService;
    public function __construct()
    {
        $this->setModel(new Topic());
        $this->notificationApiService = new NotificationApiService();
    }


    public function subscripeToTopic(TopicDTO $topicDTO, $topic): DataStatus
    {
        try {
            $tokens = UserDevice::whereIn('user_id', $topicDTO->userIds)->pluck('device_token')->toArray();
            if (count($tokens) > $topic->max) {
                return DataFailed(
                    status: false,
                    message: 'You have reached the maximum number of subscribers for this topic'
                );
            }
            $response = $this->notificationApiService->subscribeTopic($topicDTO, $tokens);
            $topic->count = $topic->count + count($tokens);
            $topic->save();
            return DataSuccess(
                status: true,
                message: 'Topic created successfully.',
                data: ' $response'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
