<?php

namespace App\Modules\Course\Application\Enums\Subscription;


enum SubscriptionTypeEnum: int
{
    case COURSE = 1;
    case LESSON = 2;
    case EXAM = 3;
}
