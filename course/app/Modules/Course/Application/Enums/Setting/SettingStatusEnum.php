<?php

namespace App\Modules\Course\Application\Enums\Setting;

enum SettingStatusEnum : int
{
    case MOVE = 1;
    case STATIC = 2;
    case SHOW_HIDE = 3;


    public static function values() {
        return [
            self::MOVE->value,
            self::STATIC->value ,
            self::SHOW_HIDE->value,
        ];
    }
}
