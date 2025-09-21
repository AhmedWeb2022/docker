<?php

namespace App\Modules\Notification\Application\DTOS\TopicUser;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class TopicUserFilterDTO extends BaseDTOAbstract
{
    public $topic_id;
    public $user_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
