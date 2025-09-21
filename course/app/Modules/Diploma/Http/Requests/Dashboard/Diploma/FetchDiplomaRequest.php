<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Diploma;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaFilterDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Course\CourseFilterDTO;

class FetchDiplomaRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaFilterDTO::class;
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
            'title' => 'nullable|string',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'word' => 'nullable|string',
            'diploma_id' => 'nullable|integer',
            'has_level' => 'nullable|boolean',
            'has_track' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'target' => 'nullable|string',
            // 'number_of_courses' => 'nullable|integer',
            'language' => 'nullable|string',
            'diploma_specialization' => 'nullable|string',
            
        ];
    }
}
