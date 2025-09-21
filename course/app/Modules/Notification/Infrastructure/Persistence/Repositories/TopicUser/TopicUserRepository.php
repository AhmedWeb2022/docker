<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Repositories\TopicUser;


use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Notification\Infrastructure\Persistence\Models\TopicUser\TopicUser;


class TopicUserRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new TopicUser());
    }
}
