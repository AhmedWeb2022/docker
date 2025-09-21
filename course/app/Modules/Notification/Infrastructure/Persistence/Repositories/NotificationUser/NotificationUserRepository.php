<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Repositories\NotificationUser;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Notification\Application\DTOS\NotificationUser\NotificationUserDTO;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Notification\Infrastructure\Persistence\Models\NotificationUser\NotificationUser;
use App\Modules\Notification\Infrastructure\Persistence\ApiService\NotificationApiService;


class NotificationUserRepository extends BaseRepositoryAbstract
{
    protected $notificationApiService;
    public function __construct()
    {
        $this->setModel(new NotificationUser());
        $this->notificationApiService = new NotificationApiService();
    }
}
