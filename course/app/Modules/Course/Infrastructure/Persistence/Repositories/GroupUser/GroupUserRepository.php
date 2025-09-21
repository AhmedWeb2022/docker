<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\GroupUser;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\GroupUser\GroupUser;

class GroupUserRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new GroupUser());
    }
}
