<?php

namespace App\Modules\Course\Application\DTOS\CourseTeacher;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CourseTeacherDTO extends BaseDTOAbstract
{
    public $course_teacher_id;
    public $teacher_id;
    public $course_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
