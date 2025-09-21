<?php

namespace App\Modules\Course\Http\Requests\ApiConnect\Course;

use Illuminate\Validation\Rule;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\ApiConnect\Course\CheckStageSubjectHasCourseDTO;

class CheckStageSubjectHasCourseRequest extends BaseRequestAbstract
{
    protected $dtoClass = CheckStageSubjectHasCourseDTO::class;
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
            'subject_stage_id' => 'required|integer',
        ];
    }
}
