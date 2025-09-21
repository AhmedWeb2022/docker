<?php

namespace App\Modules\Course\Infrastructure\Persistence\Entities\Course;

use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class CourseEntity extends Course
{
    public static function fromCourse(Course $course): static
    {
        $entity = new static();
        $entity->setRawAttributes($course->getAttributes(), true);
        $entity->setRelations($course->getRelations());
        $entity->exists = $course->exists;
        $entity->syncOriginal(); // ensure relations work properly

        return $entity;
    }

    public function getWatchedContentStatistics(int $userId, $type, $title): array
    {
        $totalVideos = $this->contents()
            ->where('type', $type)
            ->count();

        $watchedVideos = $this->contents()
            ->where('type', $type)
            ->whereHas('views', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count();

        return [
            'total_' . $title => $totalVideos,
            'watched_' . $title => $watchedVideos
        ];
    }

    public function getAllWatchedContentPercentage(int $userId): array
    {
        $totalContents = $this->contents()
            ->count();
        $watchedContents = $this->contents()
            ->whereHas('views', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count();

        return [
            'total_contents' => $totalContents,
            'watched_contents' => $watchedContents,
            'watched_contents_percentage' => $totalContents > 0
                ? round($watchedContents / $totalContents * 100, 1)
                : 0.0,
        ];
    }
}
