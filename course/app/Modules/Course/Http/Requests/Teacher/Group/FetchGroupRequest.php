<?php

namespace App\Modules\Course\Http\Requests\Teacher\Group;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Group\GroupDTO;

class FetchGroupRequest extends BaseRequestAbstract
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
            'translations' => 'nullable|array',
            'is_website' => 'nullable|boolean',
            'course_id' => 'required|exists:courses,id',
        ];
    }
}
