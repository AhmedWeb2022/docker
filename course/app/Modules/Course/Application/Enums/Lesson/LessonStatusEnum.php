<?php

namespace App\Modules\Course\Application\Enums\Lesson;


enum LessonStatusEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;
}
