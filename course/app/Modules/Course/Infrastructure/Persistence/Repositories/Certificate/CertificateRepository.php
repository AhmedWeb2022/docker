<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Certificate;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Certificate\Certificate;

class CertificateRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Certificate());
    }
}
