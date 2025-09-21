<?php

namespace App\Modules\Course\Application\Enums\View;


enum ViewTypeEnum: int
{
    case DASHBOARD = 1;
    case WEBSITE = 2;
    case MOBILE = 3;
    case API = 4;
}
