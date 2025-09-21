<?php

namespace App\Modules\Diploma\Http\Requests\Global\Diploma;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaContentCourse\DiplomaContentCourseDTO;
use Illuminate\Foundation\Http\FormRequest;

class DiplomaContentCourseIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaContentCourseDTO::class;
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
            'content_course_id' => 'required|integer|exists:diploma_content_courses,id',
        ];
    }


}
