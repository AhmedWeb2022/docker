<?php

namespace App\Modules\Course\Application\Enums\Setting;

enum SettingWatchVideoEnum : int
{
    case Specific = 1;
    case NotSpecific = 2;

    public static function values() {
        return [
            self::Specific->value,
            self::NotSpecific->value,
        ];
    }

}
