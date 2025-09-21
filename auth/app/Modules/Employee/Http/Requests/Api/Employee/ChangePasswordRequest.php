<?php

namespace App\Modules\Employee\Http\Requests\Api\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Employee\Application\DTOS\Employee\ChangePasswordDTO;


class ChangePasswordRequest extends BaseRequestAbstract
{
    protected  $dtoClass = ChangePasswordDTO::class;
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
            'old_password' => 'required|string',
            'new_password' => 'required|string',
        ];
    }

   
}
