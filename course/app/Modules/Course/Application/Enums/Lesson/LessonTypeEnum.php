<?php

namespace App\Modules\Course\Application\Enums\Lesson;


enum LessonTypeEnum: int
{
    case ONLINE = 0;
    case OFFLINE = 1;
    case BOTH = 2;
}
