<?php

namespace App\Modules\Course\Application\DTOS\Teacher\Course;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class CourseDTO extends BaseDTOAbstract
{
    public $teacher_id;
    public $course_id;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function handleSpecialCases()
    {
        $teacher = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::TEACHER->value);
        $this->teacher_id = $teacher->id;
    }
}
