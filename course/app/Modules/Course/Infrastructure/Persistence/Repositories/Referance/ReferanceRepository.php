<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Referance;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Referance\Referance;

class ReferanceRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Referance());
    }
}
