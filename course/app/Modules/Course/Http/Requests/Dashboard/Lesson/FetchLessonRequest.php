<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Lesson;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Lesson\LessonFilterDTO;

class FetchLessonRequest extends BaseRequestAbstract
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
            'translations' => 'nullable|array',
            'course_id' => 'nullable|integer|exists:courses,id',
            'parent_id' => 'nullable|integer|exists:lessons,id',
            'organization_id' => 'nullable',
            'type' => 'nullable',
            'status' => 'nullable',
            'is_free' => 'nullable',
            'is_standalone' => 'nullable',
            'price' => 'nullable',
            'image' => 'nullable',
            'with_contents' => 'nullable|integer',
            'with_children' => 'nullable|integer',
            'paginate' => 'nullable|integer',
            'only_parent' => 'nullable|boolean',
        ];
    }
}
