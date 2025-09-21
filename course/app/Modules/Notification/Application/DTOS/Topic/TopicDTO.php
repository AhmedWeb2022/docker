<?php

namespace App\Modules\Notification\Application\DTOS\Topic;


use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Notification\Application\Enums\Topic\MaxCountOfTopicEnum;

class TopicDTO extends BaseDTOAbstract
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

    public function uniqueAttributes(): array
    {
        return [
            'userIds',
        ]; // Default empty array
    }

    public function handleSpecialCases()
    {
        $this->max = MaxCountOfTopicEnum::MAX_COUNT_OF_TOPIC->value;
        $this->count = 0;
    }
}
