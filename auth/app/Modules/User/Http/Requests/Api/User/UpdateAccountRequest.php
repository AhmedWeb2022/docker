<?php

namespace App\Modules\User\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\User\Application\DTOS\User\UpdateAccountDTO;


class UpdateAccountRequest extends BaseRequestAbstract
{
    protected  $dtoClass = UpdateAccountDTO::class;

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
            'name' => 'nullable|string',
            'username' => 'nullable|string',
            'email' => 'nullable|email',
            'phone_code' => 'nullable|string',
            'phone' => 'nullable|string',
            'stage_id' => 'nullable|integer',
            'location_id' => 'nullable|integer',
            'nationality_id' => 'nullable|integer',
            'image' => 'nullable',
            'gender' => 'nullable|string',
        ];
    }
}
