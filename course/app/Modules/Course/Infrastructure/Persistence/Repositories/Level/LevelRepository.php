<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Level;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Level\Level;

class LevelRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Level());
    }
}
