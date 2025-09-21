<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Platform;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Platform\Platform;

class PlatformRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Platform());
    }
}
