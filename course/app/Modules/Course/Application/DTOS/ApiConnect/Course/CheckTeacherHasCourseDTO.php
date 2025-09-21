<?php

namespace App\Modules\Course\Application\DTOS\ApiConnect\Course;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;

class CheckTeacherHasCourseDTO extends BaseDTOAbstract
{

    public $teacher_id;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function excludedAttributes(): array
    {
        return [
            'teacher_id',
        ];
    }
}
