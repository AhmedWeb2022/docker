<?php

namespace App\Modules\Course\Application\DTOS\Rate;

use App\Modules\Base\Domain\DTO\BaseDTOAbstract;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;

class RateDTO extends BaseDTOAbstract
{
    protected bool $hasMorph = true;
    protected array $morphMap = [
        'course_id' => Course::class,
        'lesson_id' => Lesson::class,
        'diploma_id' => Diploma::class,
        // etc.
    ];
    public $rate_id;
    public $course_id;
    public $diploma_id;
    public $user_id;
    public $rateable_id;
    public $rateable_type;
    public $rate;
    public $comment;
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
                    $this->rateable_id = $value;
                    $this->rateable_type = $this->morphMap[$key];

                    return [
                        'rateable_id' => $this->rateable_id,
                        'rateable_type' => $this->rateable_type,
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
            'diploma_id',
        ]; // Default empty array
    }

    public function uniqueAttributes(): array
    {
        return [
            'user_id',
            'rateable_id',
            'rateable_type',
        ]; // Default empty array
    }
}
