<?php

namespace App\Modules\User\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\User\Application\DTOS\User\UserDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class FetchUserDetailsRequest extends BaseRequestAbstract
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
        ];
    }

}
