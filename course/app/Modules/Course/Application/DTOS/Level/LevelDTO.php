<?php

namespace App\Modules\Course\Application\DTOS\Level;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LevelDTO extends BaseDTOAbstract
{
    public $level_id;
    public $translations;
    public $organization_id;
    public $image;
    public $parent_id;
    public $is_standalone;
    protected string $imageFolder = 'level';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
