<?php

namespace App\Modules\Diploma\Application\DTOS\Diploma;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;

class AddLevelDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;

    public $levels; // Array of levels to be added
    public $translations;
    public $diploma_id;
    public $level_ids;
    public $level_id;
    public $image;
    public $has_track;
    public $tracks;
    public $contents;
    public $courses_ids;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'level_ids',
            'tracks',
            'courses_ids',
        ];
    }
}
