<?php

namespace App\Modules\Course\Application\DTOS\SubscribedClient;

use ReflectionClass;
use ReflectionProperty;
use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

class SubscribedClientDTO extends BaseDTOAbstract
{
    public $subscribed_client_id;
    public $organization_id;
    public $email;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
