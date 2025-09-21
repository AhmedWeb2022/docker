<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\ContentCourse;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaContentCourse\DiplomaContentCourseDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevel\DiplomaLevelDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Diploma\Application\DTOS\Level\LevelFilterDTO;
use Illuminate\Validation\Rule;

class FetchContentCourseRequest extends BaseRequestAbstract
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
            'diploma_content_id' => 'nullable|integer|exists:diploma_contents,id',
            'course_id' => 'nullable|integer|exists:courses,id',
        ];
    }
}
