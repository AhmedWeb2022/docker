<?php

namespace App\Modules\Employee\Http\Requests\Api\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Employee\Application\DTOS\Employee\RegisterDTO;

class RegisterRequest extends BaseRequestAbstract
{
    protected  $dtoClass = RegisterDTO::class;
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
            'name' => 'required|string',
            'username' => 'nullable|string|unique:employees,username',
            'phone' => 'required|string|unique:employees,phone',
            'identify_number' => 'required|string|unique:employees,id_number',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string',
            'device_token' => 'nullable|string',
            'device_id' => 'nullable|string',
            'device_type' => 'nullable|string',
            'device_os' => 'nullable|string',
            'device_os_version' => 'nullable|string',
        ];
    }


}
