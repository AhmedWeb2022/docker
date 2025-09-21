<?php

namespace App\Modules\Diploma\Application\DTOS\Diploma;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class DiplomaFilterDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $diploma_id;
    public $title;
    public $word;
    public $short_description;
    public $full_description;
    public $main_image;
    public $has_level;
    public $has_track;
    public $start_date;
    public $end_date;
    public $target;
    public $limit;
    public $paginate;
    public $created_by;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'with_lessons',
            'excludeAttributes',
            'limit',
            'paginate',
            'word'
        ]; // Default empty array
    }
}
