<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Lesson;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Lesson\LessonDTO;
use App\Modules\Course\Application\Enums\Course\CourseLevelTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

class UpdateLessonRequest extends BaseRequestAbstract
{
    protected $dtoClass = LessonDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function customRules(): array
    {
        return [
            'lesson_id' => 'required|integer|exists:lessons,id',
            'translations' => 'required|array',
            'course_id' => 'required|integer|exists:courses,id',
            'parent_id' => 'nullable|integer|exists:lessons,id',
            'organization_id' => 'nullable',
            'type' => 'nullable',
            'status' => 'nullable',
            'is_free' => 'nullable',
            'is_standalone' => 'nullable|boolean',
            'price' => 'nullable',
            'image' => 'nullable',
            'video' => 'nullable|array',
            'video.is_file' => 'nullable|in:0,1',
            'video.video_type' => 'nullable',
            'video.file' => 'required_if:video.is_file,1',
            'video.link' => 'required_if:video.is_file,0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $courseId = $this->get('course_id');
            $parentId = $this->get('parent_id');
            $hasParentId = $this->has('parent_id');
            $hasStandalone = $this->has('is_standalone');
            $isStandalone = filter_var($this->get('is_standalone'), FILTER_VALIDATE_BOOLEAN);
            $lessonId = $this->get('lesson_id');

            $course = Course::find($courseId);
            if (!$course) {
                $validator->errors()->add('course_id', 'Course not found.');
                return;
            }

            if ($course->level_type == CourseLevelTypeEnum::HAS_ONLY_CONTENT->value) {
                $validator->errors()->add('course_id', 'This course\'s level type does not support lesson existence.');
                return;
            }

            if ($course->level_type == CourseLevelTypeEnum::HAS_UNIT_AND_LEVEL_AND_CONTENT->value) {
                $existingLessons = $course->lessons()->where('id', '!=', $lessonId)->count();
                $hasStandaloneLesson = $course->lessons()->where('is_standalone', true)->where('id', '!=', $lessonId)->exists();

                if ($existingLessons === 0) {
                    if ($hasParentId && !is_null($parentId)) {
                        $validator->errors()->add('parent_id', 'The parent_id must not be sent when this is the only lesson.');
                    }
                    if (!$hasStandalone || !$isStandalone) {
                        $validator->errors()->add('is_standalone', 'The only lesson must be standalone (is_standalone = true).');
                    }
                } else {
                    if ($hasParentId && !is_null($parentId)) {
                        if (!$hasStandaloneLesson) {
                            $validator->errors()->add('parent_id', 'You cannot assign a parent unless the course has a standalone lesson.');
                            return;
                        }

                        if ($hasStandalone) {
                            $validator->errors()->add('is_standalone', 'The is_standalone field must not be sent when parent_id is present.');
                        }

                        $parentLesson = Lesson::find($parentId);
                        if (!$parentLesson) {
                            $validator->errors()->add('parent_id', 'The selected parent lesson does not exist.');
                            return;
                        }

                        if ($parentLesson->course_id != $courseId) {
                            $validator->errors()->add('parent_id', 'The parent lesson must belong to the same course.');
                        }
                    } else {
                        if (!$hasStandalone || !$isStandalone) {
                            $validator->errors()->add('is_standalone', 'The is_standalone field is required and must be true when parent_id is not provided.');
                        }
                    }
                }
            } else {
                if ($hasParentId) {
                    $validator->errors()->add('parent_id', 'The parent_id field must not be sent when course level_type is not 3.');
                }

                if (!$hasStandalone) {
                    $validator->errors()->add('is_standalone', 'The is_standalone field is required when parent_id is not present.');
                }

                if ($hasParentId && $hasStandalone) {
                    $validator->errors()->add('is_standalone', 'The is_standalone field must not be sent when parent_id is present.');
                }
            }
        });
    }
}
