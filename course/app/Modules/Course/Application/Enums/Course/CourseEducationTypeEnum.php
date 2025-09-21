<?php

namespace App\Modules\Course\Application\Enums\Course;

use App\Traits\HasMetadata;

enum CourseEducationTypeEnum: int
{
    use HasMetadata;
    case ONLLINE = 1;
    case OFFLINE = 2;
    case BOTH = 3;

    public function getMetadata(): array
    {
        return [
            self::ONLLINE->value =>
            [
                'label' => 'Online',]
            ,
            self::OFFLINE->value => [
                'label' => 'Offline',
            ],
            self::BOTH->value => [
                'label' => 'Both',
            ]
        ];
    }

    public static function values() {
        return [
            self::ONLLINE->value ,
            self::OFFLINE->value,
            self::BOTH->value,
        ];
    }
}
