<?php

namespace App\Modules\Course\Application\DTOS\CourseSubjectStage;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CourseSubjectStageDTO extends BaseDTOAbstract
{
    public $course_id;
    public  $subject_stage_id;
    public  $subject_id;
    public  $stage_id;
    public $course_subject_stage_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
