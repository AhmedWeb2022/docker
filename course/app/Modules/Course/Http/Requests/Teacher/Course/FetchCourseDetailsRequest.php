<?php

namespace App\Modules\Course\Http\Requests\Teacher\Course;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Course\CourseDTO;
use Illuminate\Validation\Rule;

class FetchCourseDetailsRequest extends BaseRequestAbstract
{
    protected $dtoClass = CourseDTO::class;
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
            "course_id" => 'required|integer|exists:courses,id',
        ];
    }
}
