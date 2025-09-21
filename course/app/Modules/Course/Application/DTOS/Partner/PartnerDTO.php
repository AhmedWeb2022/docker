<?php

namespace App\Modules\Course\Application\DTOS\Partner;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class PartnerDTO extends BaseDTOAbstract
{
    public $partner_id;
    public $translations;
    public $organization_id;
    public $image;
    public $cover;
    public $link;
    public $is_website;
    protected string $imageFolder = 'partner';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
