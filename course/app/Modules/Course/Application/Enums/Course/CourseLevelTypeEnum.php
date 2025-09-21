<?php

namespace App\Modules\Course\Application\Enums\Course;

use App\Traits\HasMetadata;

enum CourseLevelTypeEnum: int
{
    use HasMetadata;
    case HAS_ONLY_CONTENT = 1;
    case HAS_LEVEL_AND_CONTENT = 2;
    case HAS_UNIT_AND_LEVEL_AND_CONTENT = 3;


    public function getMetadata(): array
    {
        return match ($this) {
            self::HAS_ONLY_CONTENT => [
                'label' => 'Only Content',
            ],
            self::HAS_LEVEL_AND_CONTENT => [
                'label' => 'Level and Content',
            ],
            self::HAS_UNIT_AND_LEVEL_AND_CONTENT => [
                'label' => 'Unit and Level and Content',
            ],
            default => [
                'label' => '',
            ]
        };
    }

    public static function values() {
        return [
            self::HAS_ONLY_CONTENT->value,
            self::HAS_LEVEL_AND_CONTENT->value,
            self::HAS_UNIT_AND_LEVEL_AND_CONTENT->value,
        ];
    }
}
