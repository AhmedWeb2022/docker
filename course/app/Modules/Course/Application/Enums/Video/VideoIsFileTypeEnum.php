<?php

namespace App\Modules\Course\Application\Enums\Video;


enum VideoIsFileTypeEnum: int
{
    case NOT_FILE = 0;
    case FILE = 1;


    public static function values(): array
    {
        return [
            self::NOT_FILE->value ,
            self::FILE->value
        ];
    }
}
