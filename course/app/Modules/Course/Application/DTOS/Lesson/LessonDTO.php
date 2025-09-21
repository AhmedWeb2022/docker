<?php

namespace App\Modules\Course\Application\DTOS\Lesson;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class LessonDTO extends BaseDTOAbstract
{
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
    public $video;
    public $created_by;
    public $updated_by;
    public $is_separately_sold;
    protected string $imageFolder = 'lesson';
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
