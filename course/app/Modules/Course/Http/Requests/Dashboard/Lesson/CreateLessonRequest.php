<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Lesson;

use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Lesson\LessonDTO;
use App\Modules\Course\Application\Enums\Lesson\LessonTypeEnum;
use App\Modules\Course\Application\Enums\Course\CourseLevelTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

class CreateLessonRequest extends BaseRequestAbstract
{
    protected $dtoClass = LessonDTO::class;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function CustomRules(): array
    {

        $course = Course::find($this->get('course_id'));
        return [ // data validation
            'translations' => 'required|array',
            'course_id' => 'required|integer|exists:courses,id',
            'parent_id' => [
                'nullable', // base rule
                'integer',
                'exists:lessons,id',
            ],
            'organization_id' => 'nullable',
            'type' => ['required', new Enum(LessonTypeEnum::class)],
            'status' => 'nullable',
            'is_separately_sold' => 'nullable',
            'is_free' => 'nullable',
            'is_standalone' => [
                'nullable',
                'boolean',
            ],
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

            $course = Course::find($courseId);

            if (!$course) {
                $validator->errors()->add('course_id', 'Course not found.');
                return;
            }

            // ❌ Block lesson creation if course.level_type == 1
            if ($course->level_type == CourseLevelTypeEnum::HAS_ONLY_CONTENT->value) {
                $validator->errors()->add('course_id', 'This course\'s level type does not support lesson existence.');
                return;
            }

            // ✅ Logic for level_type == 3
            if ($course->level_type == CourseLevelTypeEnum::HAS_UNIT_AND_LEVEL_AND_CONTENT->value) {
                $existingLessons = $course->lessons()->count();
                $hasStandaloneLesson = $course->lessons()->where('is_standalone', true)->exists();

                if ($existingLessons === 0) {
                    // First lesson: must be standalone
                    if ($hasParentId && !is_null($parentId)) {
                        $validator->errors()->add('parent_id', 'The parent_id must not be sent when creating the first lesson.');
                    }
                    if (!$hasStandalone || !$isStandalone) {
                        $validator->errors()->add('is_standalone', 'The first lesson must be standalone (is_standalone = true).');
                    }
                } else {
                    if ($hasParentId && !is_null($parentId)) {
                        // Creating a child lesson — must be allowed only if standalone lessons exist
                        if (!$hasStandaloneLesson) {
                            $validator->errors()->add('parent_id', 'You cannot create a child lesson unless the course has a standalone lesson.');
                            return;
                        }

                        if ($hasStandalone) {
                            $validator->errors()->add('is_standalone', 'The is_standalone field must not be sent when creating a child lesson.');
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
                        // Creating another standalone lesson
                        if (!$hasStandalone || !$isStandalone) {
                            $validator->errors()->add('is_standalone', 'The is_standalone field is required and must be true when not assigning a parent.');
                        }
                    }
                }
            } else {
                // For level_type != 3
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
