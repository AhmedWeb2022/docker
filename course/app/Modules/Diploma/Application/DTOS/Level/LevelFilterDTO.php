<?php

namespace App\Modules\Diploma\Application\DTOS\Level;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LevelFilterDTO extends BaseDTOAbstract
{
    public $level_id;
    public $translations;
    public $image;
    public $diploma_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'diploma_id',
        ];
    }
}
