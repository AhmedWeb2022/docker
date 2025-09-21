<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Course;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Course\CourseLevelDTO;
use App\Modules\Course\Application\DTOS\Course\CourseFilterDTO;

class FetchCourseLevelRequest extends BaseRequestAbstract
{
    protected $dtoClass = CourseLevelDTO::class;
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
            'course_id' => 'required|integer|exists:courses,id',
        ];
    }
}
