<?php

namespace App\Modules\Course\Application\Enums\Content;


enum ContentTypeEnum: int
{
    case SESSION = 1;
    case AUDIO = 2;
    case DOCUMENT = 3;
    case POLL = 4;
    case EXAM = 5;
    case LIVE = 6;
    case QUESTION = 7;


    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
