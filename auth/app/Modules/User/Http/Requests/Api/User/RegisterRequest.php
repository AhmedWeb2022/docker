<?php

namespace App\Modules\User\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\User\Application\DTOS\User\RegisterDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class RegisterRequest extends BaseRequestAbstract
{
    protected  $dtoClass = RegisterDTO::class;
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
            'name' => 'required|string',
            'username' => 'nullable|string|unique:users,username',
            'phone_code' => 'nullable|string',
            'phone' => 'required|string|unique:users,phone',
            'identify_number' => 'required|string|unique:users,id_number',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'device_token' => 'nullable|string',
            'device_id' => 'nullable|string',
            'device_type' => 'nullable|string',
            'device_os' => 'nullable|string',
            'device_os_version' => 'nullable|string',
            'location_id' => 'nullable|integer',
            'nationality_id' => 'nullable|integer',
        ];
    }
}
