<?php

namespace App\Modules\Notification\Application\DTOS\NotificationUser;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class NotificationUserDTO extends BaseDTOAbstract
{
    public $topic_id;
    public $notification_id;
    public $user_id;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
