<?php

namespace App\Modules\User\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\User\Application\DTOS\User\UserDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class UpdateUserRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UserDTO::class;
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
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'nullable|string',
            'username' => 'nullable|string|unique:users,username,' . $this->user_id,
            'phone' => 'nullable|string|unique:users,phone,' . $this->user_id,
            'identify_number' => 'nullable|string|unique:users,id_number,' . $this->user_id,
            'email' => 'nullable|email|unique:users,email,' . $this->user_id,
            'password' => 'nullable|string',
        ];
    }
}
