<?php

namespace App\Modules\Diploma\Application\DTOS\DiplomaTarget;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class DiplomaTargetDTO extends BaseDTOAbstract
{
    public $diploma_id;
    public $translations;
    public $is_active;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
