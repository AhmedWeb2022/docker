<?php

namespace App\Modules\Admin\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Admin\Application\DTOS\Admin\AdminDTO;

class CreateAdminRequest extends BaseRequestAbstract
{
    protected  $dtoClass = AdminDTO::class;
    /**
     * Determine if the Admin is authorized to make this request.
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
            'username' => 'nullable|string|unique:admins,username',
            'phone' => 'required|string|unique:admins,phone',
            'identify_number' => 'required|string|unique:admins,id_number',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string',
            'image' => 'nullable|string',
        ];
    }


}
