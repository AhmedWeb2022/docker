<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\ContentCourse;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaContent\DiplomaContentDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaContentCourse\DiplomaContentCourseDTO;
use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateContentCourseRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaContentCourseDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function customRules(): array
    {
        return [ // data validation
            'diploma_content_id' => 'required|integer|exists:diploma_contents,id',
            'course_id' => 'required|integer|exists:courses,id',
        ];
    }

}
