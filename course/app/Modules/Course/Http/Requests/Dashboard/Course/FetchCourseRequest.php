<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Course;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Course\CourseFilterDTO;
use App\Modules\Course\Application\Enums\Course\CourseLevelTypeEnum;
use Illuminate\Validation\Rule;

class FetchCourseRequest extends BaseRequestAbstract
{
    protected $dtoClass = CourseFilterDTO::class;
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
    public function customRules(): array
    {
        return [ // data validation
            'course_id' => 'nullable|integer',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'word' => 'nullable|string',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',
            'parent_id' => 'nullable|integer',
            'organization_id' => 'nullable|integer',
            'stage_id' => 'nullable|array', // education_type
            'subject_id' => 'nullable|integer',
            'teacher_id' => 'nullable|integer',
            'certificate_id' => 'nullable|integer|exists:certificates,id', // has_certificate
            'user_id' => 'nullable|integer',
            'mine' => 'nullable|boolean',
            'type' => 'nullable|in:course,document,video,audio',
            'with_lessons' => 'nullable|boolean',
            'status' => 'nullable|in:active,inactive,archived',
            'level_type' => [
                'nullable',
                'integer',
                Rule::enum(CourseLevelTypeEnum::class),
            ],
            'rate' => 'nullable|array',
            'rate.*' => 'numeric|min:0|max:5',
            "has_discount" => "nullable|boolean",
            "is_certificate" => "nullable|boolean",
            "has_hidden" => "nullable|boolean",
            'has_favourite' => 'nullable|boolean',
            "is_free" => "nullable|boolean",
        ];
    }
}
