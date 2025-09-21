<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\CourseTeacher;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseTeacher\CourseTeacher;

class CourseTeacherRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new CourseTeacher());
    }
}
