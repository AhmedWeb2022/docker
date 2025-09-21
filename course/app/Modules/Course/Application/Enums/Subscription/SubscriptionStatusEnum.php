<?php

namespace App\Modules\Course\Application\Enums\Subscription;


enum SubscriptionStatusEnum: int
{
    case PENDING = 1;
    case SUCCESS = 2;
    case FAILED = 3;
    case CANCELED = 4;
    case REFUNDED = 5;
}
