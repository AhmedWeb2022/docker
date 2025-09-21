<?php

namespace App\Modules\Notification\Application\DTOS\TopicUser;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class TopicUserDTO extends BaseDTOAbstract
{
    public $topic_id;
    public $user_id;
    public $type;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
