<?php

namespace App\Modules\Course\Http\Requests\Global\Lesson;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Lesson\LessonFilterDTO;

class LessonIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = LessonFilterDTO::class;
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
            'lesson_id' => 'required|integer|exists:lessons,id',

            'with_contents' => 'nullable|integer',
            'with_children' => 'nullable|integer',
        ];
    }
}
