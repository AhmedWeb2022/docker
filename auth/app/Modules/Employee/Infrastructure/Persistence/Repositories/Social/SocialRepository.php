<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Repositories\Social;

use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Employee\Infrastructure\Persistence\Models\Social\Social;

class SocialRepository extends BaseRepositoryAbstract
{

    public function __construct()
    {
        $this->setModel(new Social());
    }

}
