<?php

namespace App\Modules\Course\Application\DTOS\Lesson;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LessonFilterDTO extends BaseDTOAbstract
{
    protected bool $excludeAttributes = true;
    public $course_id;
    public $translations;
    public $parent_id;
    public $organization_id;
    public $lesson_id;
    public $is_free;
    public $is_standalone;
    public $type;
    public $status;
    public $price;
    public $image;
    public $with_contents;
    public $limit;
    public $paginate;
    public $with_children;
    public $is_separately_sold;
    public $only_parent;
    protected string $imageFolder = 'lesson';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'with_contents',
            'only_parent',
            'limit',
            'paginate',
            'with_children'
        ]; // Default empty array
    }
}
