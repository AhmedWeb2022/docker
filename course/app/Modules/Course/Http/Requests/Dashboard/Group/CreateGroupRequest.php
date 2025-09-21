<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Group;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Group\GroupDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateGroupRequest extends BaseRequestAbstract
{
    protected $dtoClass = GroupDTO::class;
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
    public function CustomRules(): array
    {
        return [ // data validation
            'translations' => 'required|array',
            'image' => 'required',
            'course_id' => 'required|integer|exists:courses,id',
            'student_ids' => 'required|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }
}
