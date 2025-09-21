<?php

namespace App\Modules\Notification\Application\DTOS\Topic;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class TopicFilterDTO extends BaseDTOAbstract
{
    public $topic_id;
    public $name;
    public $type;
    public $max;
    public $count;
    public $userIds;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
