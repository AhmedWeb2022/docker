<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Platform;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseDependency\CoursePlatform;

class CoursePlatformRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new CoursePlatform());
    }
}
