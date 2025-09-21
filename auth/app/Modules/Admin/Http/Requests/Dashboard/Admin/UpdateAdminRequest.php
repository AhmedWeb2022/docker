<?php

namespace App\Modules\Admin\Http\Requests\Dashboard\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Admin\Application\DTOS\Admin\AdminDTO;

class UpdateAdminRequest extends BaseRequestAbstract
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
            'admin_id' => 'required|integer|exists:admins,id',
            'name' => 'nullable|string',
            'username' => 'nullable|string|unique:admins,username,' . $this->admin_id,
            'phone' => 'nullable|string|unique:admins,phone,' . $this->admin_id,
            'identify_number' => 'nullable|string|unique:admins,id_number,' . $this->admin_id,
            'email' => 'nullable|email|unique:admins,email,' . $this->admin_id,
            'password' => 'nullable|string',
        ];
    }


}
