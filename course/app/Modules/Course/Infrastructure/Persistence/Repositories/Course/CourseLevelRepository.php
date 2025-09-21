<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Course;


use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseDependency\CoursePayment;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseLevel\CourseLevel;

class CourseLevelRepository extends BaseRepositoryAbstract
{
    public function __construct()
    {
        $this->setModel(new CourseLevel());
    }
}
