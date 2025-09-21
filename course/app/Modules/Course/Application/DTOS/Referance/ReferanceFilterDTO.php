<?php

namespace App\Modules\Course\Application\DTOS\Referance;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class ReferanceFilterDTO extends BaseDTOAbstract
{
    public $partner_id;
    public $translations;
    public $organization_id;
    public $image;
    public $cover;
    public $link;
    public $referance_id;
    protected string $imageFolder = 'referance';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
