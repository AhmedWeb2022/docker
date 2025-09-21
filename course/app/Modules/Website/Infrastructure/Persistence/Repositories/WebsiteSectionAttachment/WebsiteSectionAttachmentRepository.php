<?php

namespace App\Modules\Website\Infrastructure\Persistence\Repositories\WebsiteSectionAttachment;

use Exception;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionAttachment\WebsiteSectionAttachment;

class WebsiteSectionAttachmentRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new WebsiteSectionAttachment());
    }
}
