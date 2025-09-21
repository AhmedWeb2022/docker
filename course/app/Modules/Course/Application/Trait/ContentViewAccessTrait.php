<?php

namespace App\Modules\Course\Application\Trait;

use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Illuminate\Support\Collection;

trait ContentViewAccessTrait
{
    // public function canUserViewContent(Content $content, int $userId): bool
    // {
    //     // 1. If content is skippable
    //     if ($content->can_skip) {
    //         // Check if content ckip_rate < = to content view stops
    //         if ($content->skip_rate > $content->userViewStops($userId)) {

    //             return false;
    //         }
    //         return true;
    //     }

    //     // 2. If course-level content without lesson
    //     if ($content->course_id && !$content->lesson_id) {
    //         $prev = Content::where('course_id', $content->course_id)
    //             ->whereNull('lesson_id')
    //             ->where('order', '<', $content->order)
    //             ->orderByDesc('order')
    //             ->limit(1)
    //             ->with(['views' => function ($q) use ($userId) {
    //                 $q->where('user_id', $userId);
    //             }])
    //             ->first();

    //         return !$prev || $prev->can_skip || $prev->views->isNotEmpty();
    //     }

    //     // 3. If lesson content
    //     if ($content->lesson_id && $content->course_id) {
    //         // Fetch all related contents and views in one go
    //         $allLessons = $content->course->lessons()
    //             ->orderBy('order')
    //             ->with(['contents' => function ($q) {
    //                 $q->orderBy('order');
    //             }, 'contents.views' => function ($q) use ($userId) {
    //                 $q->where('user_id', $userId);
    //             }])->get();

    //         // Get current lesson position
    //         $currentLessonIndex = $allLessons->search(fn($l) => $l->id === $content->lesson_id);
    //         if ($currentLessonIndex === false) return false;

    //         // Check previous content
    //         $prevContent = Content::where('course_id', $content->course_id)
    //             ->where('order', '<', $content->order)
    //             ->orderByDesc('order')
    //             ->limit(1)
    //             ->with(['views' => function ($q) use ($userId) {
    //                 $q->where('user_id', $userId);
    //             }])
    //             ->first();

    //         if (!$prevContent || $prevContent->can_skip || $prevContent->views->isNotEmpty()) {
    //             // Now check all previous lessons' content views
    //             $prevLessons = $allLessons->slice(0, $currentLessonIndex);

    //             foreach ($prevLessons as $lesson) {
    //                 foreach ($lesson->contents as $c) {
    //                     if (!$c->can_skip && $c->views->isEmpty()) {
    //                         return false;
    //                     }
    //                 }
    //             }

    //             return true;
    //         }

    //         return false;
    //     }

    //     return false;
    // }

    public function canUserViewContent(Content $content, int $userId): bool
    {
        // 1. If this is the first content (no previous)
        $hasLesson = $content->lesson_id !== null;
        $query = Content::query()
            ->where('course_id', $content->course_id)
            ->when(!$hasLesson, fn($q) => $q->whereNull('lesson_id'))
            ->when($hasLesson, fn($q) => $q->where('lesson_id', $content->lesson_id))
            ->where('order', '<', $content->order)
            ->orderByDesc('order')
            ->limit(1)
            ->with(['views' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);

        $prevContent = $query->first();

        // 2. If no previous content, it's the first one → always allowed
        if (!$prevContent) {
            return true;
        }

        // 3. Handle skipping logic
        if ($prevContent->can_skip) {
            // Check if user meets the skip rate condition of previous content
            $viewStops = $prevContent->userViewStops($userId);
            if ($viewStops < $prevContent->skip_rate) {
                return false;
            }

            return true;
        }

        // 4. If previous content is not skippable → check if it's viewed
        if ($prevContent->views->isEmpty()) {
            return false;
        }

        // 5. If lesson content, check all previous lessons content as well
        if ($hasLesson) {
            $allLessons = $content->course->lessons()
                ->orderBy('order')
                ->with(['contents' => function ($q) {
                    $q->orderBy('order');
                }, 'contents.views' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }])->get();

            $currentLessonIndex = $allLessons->search(fn($l) => $l->id === $content->lesson_id);
            if ($currentLessonIndex === false) return false;

            $prevLessons = $allLessons->slice(0, $currentLessonIndex);

            foreach ($prevLessons as $lesson) {
                foreach ($lesson->contents as $c) {
                    if (!$c->can_skip && $c->views->isEmpty()) {
                        return false;
                    }

                    if ($c->can_skip && $c->userViewStops($userId) < $c->skip_rate) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
