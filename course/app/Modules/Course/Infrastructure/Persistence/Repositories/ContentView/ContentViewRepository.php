<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\ContentView;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\ContentView\ContentView;

class ContentViewRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new ContentView());
    }
}
