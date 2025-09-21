<?php

namespace App\Modules\Employee\Application\Enums;


enum EmployeeTypeEnum: int
{
    case EMPLOYEE = 1;
    case TEACHER = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
