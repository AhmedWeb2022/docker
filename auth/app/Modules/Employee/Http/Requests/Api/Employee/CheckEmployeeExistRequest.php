<?php

namespace App\Modules\Employee\Http\Requests\Api\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Employee\Application\Enums\EmployeeTypeEnum;
use App\Modules\Employee\Application\DTOS\Employee\EmployeeFilterDTO;
use App\Modules\Employee\Application\DTOS\Employee\CheckEmployeeExistDTO;

class CheckEmployeeExistRequest extends BaseRequestAbstract
{
    protected  $dtoClass = CheckEmployeeExistDTO::class;

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
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'required|integer|exists:employees,id',
            'role' => 'nullable|integer|in:' . implode(',', EmployeeTypeEnum::values()), // Ensure role is one of the defined EmployeeTypeEnum values
        ];
    }
}
