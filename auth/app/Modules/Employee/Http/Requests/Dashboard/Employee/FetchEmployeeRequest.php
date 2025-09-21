<?php

namespace App\Modules\Employee\Http\Requests\Dashboard\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Employee\Application\Enums\EmployeeTypeEnum;
use App\Modules\Employee\Application\DTOS\Employee\EmployeeFilterDTO;

class FetchEmployeeRequest extends BaseRequestAbstract
{
    protected  $dtoClass = EmployeeFilterDTO::class;
    /**
     * Determine if the Employee is authorized to make this request.
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
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'word' => 'nullable|string',
            'subject_stage_id' => 'nullable|array',
            'role' => 'nullable|integer|in:' . implode(',', EmployeeTypeEnum::values()), // Ensure role is one of the defined EmployeeTypeEnum values
        ];
    }
}
