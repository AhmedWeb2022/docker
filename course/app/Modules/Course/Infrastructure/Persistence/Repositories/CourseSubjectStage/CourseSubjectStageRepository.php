<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\CourseSubjectStage;



use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseSubjectStage\CourseSubjectStage;

class CourseSubjectStageRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new CourseSubjectStage());
    }
}
