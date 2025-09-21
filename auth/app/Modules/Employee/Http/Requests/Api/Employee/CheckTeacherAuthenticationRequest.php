<?php

namespace App\Modules\Employee\Http\Requests\Api\Employee;

use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Employee\Application\Enums\EmployeeTypeEnum;
use App\Modules\Employee\Application\DTOS\Employee\CheckAuthenticationDTO;


class CheckTeacherAuthenticationRequest extends BaseRequestAbstract
{
    protected  $dtoClass = CheckAuthenticationDTO::class;
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
            'role' => 'required|integer|in:' . implode(',', EmployeeTypeEnum::values()), // Ensure the role is either teacher or admin
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->role && !in_array($this->role, EmployeeTypeEnum::values())) {
                $validator->errors()->add('role', 'The selected role is invalid.');
            }

            $token = request()->bearerToken();
            $accessToken = PersonalAccessToken::findToken($token);
            if (!$accessToken) {
                $validator->errors()->add('token', 'Unauthinticated - Authentication required');
            }
            $teacher = $accessToken->tokenable()->where('role', $this->role);
            if (!$teacher) {
                $validator->errors()->add('token', 'Unauthinticated - Authentication required');
            }
        });
    }
}
