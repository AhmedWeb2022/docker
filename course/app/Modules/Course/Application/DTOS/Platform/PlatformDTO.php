<?php

namespace App\Modules\Course\Application\DTOS\Platform;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class PlatformDTO extends BaseDTOAbstract
{
    public $platform_id;
    public $translations;
    public $organization_id;
    public $image;
    public $cover;
    public $link;
    public $slug;
    protected string $imageFolder = 'platform';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
