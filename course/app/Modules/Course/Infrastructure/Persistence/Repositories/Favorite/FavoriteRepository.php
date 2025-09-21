<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Favorite;


use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Favorite\Favorite;

class FavoriteRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new Favorite());
    }
}
