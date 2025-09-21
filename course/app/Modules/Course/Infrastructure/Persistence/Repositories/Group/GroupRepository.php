<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Group;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Group\Group;

class GroupRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Group());
    }
}
