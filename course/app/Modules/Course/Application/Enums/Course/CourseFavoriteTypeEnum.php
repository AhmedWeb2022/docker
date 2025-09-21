<?php

namespace App\Modules\Course\Application\Enums\Course;

use App\Traits\HasMetadata;

enum CourseFavoriteTypeEnum: int
{
    use HasMetadata;
    case FAVOURITE = 1;
    case NOT_FAVOURITE = 0;

    public function getMetadata(): array
    {
        return [
            self::FAVOURITE->value =>
            [
                'label' => 'FAVOURITE',
            ],
            self::NOT_FAVOURITE->value => [
                'label' => 'NOT_FAVOURITE',
            ],
        ];
    }

    public static function values()
    {
        return [
            self::FAVOURITE->value,
            self::NOT_FAVOURITE->value,
        ];
    }
}
