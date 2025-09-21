<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Partner;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Partner\Partner;

class PartnerRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Partner());
    }
}
