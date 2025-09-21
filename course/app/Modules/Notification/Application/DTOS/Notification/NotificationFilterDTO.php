<?php

namespace App\Modules\Notification\Application\DTOS\Notification;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class NotificationFilterDTO extends BaseDTOAbstract
{
    public $notification_id;
    public $title;
    public $subtitle;
    public $image;
    public $url;
    public $notifiable_id;
    public $notifiable_type;
    public $userIds;
    public $tobic_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
