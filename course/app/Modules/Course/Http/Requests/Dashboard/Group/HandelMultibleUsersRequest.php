<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Group;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Group\GroupFilterDTO;

class HandelMultibleUsersRequest extends BaseRequestAbstract
{
    protected $dtoClass = GroupFilterDTO::class;
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
            'student_ids' => 'required|array',
        ];
    }
}
