<?php

namespace App\Modules\Diploma\Application\DTOS\Level;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LevelDTO extends BaseDTOAbstract
{
    public $level_id;
    public $translations;
    public $image;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
