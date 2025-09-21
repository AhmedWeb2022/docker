<?php

namespace App\Modules\Admin\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Admin\Application\DTOS\Admin\ChangePasswordDTO;


class ChangePasswordRequest extends BaseRequestAbstract
{
    protected  $dtoClass = ChangePasswordDTO::class;
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
            'old_password' => 'required|string',
            'new_password' => 'required|string',
        ];
    }


}
