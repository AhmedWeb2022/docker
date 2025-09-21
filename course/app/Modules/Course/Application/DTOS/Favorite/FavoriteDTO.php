<?php

namespace App\Modules\Course\Application\DTOS\Favorite;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

class FavoriteDTO extends BaseDTOAbstract
{
    protected bool $hasMorph = true;
    protected array $morphMap = [
        'course_id' => Course::class,
        'lesson_id' => Lesson::class,
        // etc.
    ];
    public $favorite_id;
    public $course_id;
    public $user_id;
    public $favoritable_id;
    public $favoritable_type;
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    public function getMorphFields($data)
    {
        if ($this->morphMap == []) {
            throw new \Exception("Morph map is not defined in " . get_class($this));
        } else {
            foreach ($data as $key => $value) {
                if (isset($this->morphMap[$key])) {
                    $this->favoritable_id = $value;
                    $this->favoritable_type = $this->morphMap[$key];

                    return [
                        'favoritable_id' => $this->favoritable_id,
                        'favoritable_type' => $this->favoritable_type,
                    ];
                }
            }
        }
        return [];
    }
    public function excludedAttributes(): array
    {
        return [
            'course_id',
        ]; // Default empty array
    }

    // public function uniqueAttributes(): array
    // {
    //     return [
    //         'user_id',
    //         'favoritable_id',
    //         'favoritable_type',
    //     ]; // Default empty array
    // }
}
