<?php

namespace App\Modules\Website\Infrastructure\Persistence\Repositories\WebsiteSection;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSection\WebsiteSection;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;

class WebsiteSectionRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new WebsiteSection());
    }
}
