<?php

namespace App\Modules\Website\Infrastructure\Persistence\Repositories\WebsiteSectionContent;

use Exception;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionContent\WebsiteSectionContent;

class WebsiteSectionContentRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new WebsiteSectionContent());
    }
}
