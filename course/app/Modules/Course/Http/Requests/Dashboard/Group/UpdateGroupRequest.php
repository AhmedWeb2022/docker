<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Group;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Group\GroupDTO;

class UpdateGroupRequest extends BaseRequestAbstract
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
    public function customRules(): array
    {
        return [ // data validation
            'group_id' => 'required|integer|exists:groups,id',
            'translations' => 'required|array',
            'image' => 'required',
            'course_id' => 'required|integer|exists:courses,id',
            'student_ids' => 'prohibited',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }
}
