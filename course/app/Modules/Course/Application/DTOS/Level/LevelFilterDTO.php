<?php

namespace App\Modules\Course\Application\DTOS\Level;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LevelFilterDTO extends BaseDTOAbstract
{
    public $level_id;
    public $translations;
    public $organization_id;
    public $image;
    public $parent_id;
    public $is_standalone;
    public $course_id;
    protected string $imageFolder = 'level';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'course_id',
        ];
    }
}
