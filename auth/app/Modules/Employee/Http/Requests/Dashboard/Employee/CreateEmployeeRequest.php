<?php

namespace App\Modules\Employee\Http\Requests\Dashboard\Employee;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Employee\Application\Enums\EmployeeTypeEnum;
use App\Modules\Employee\Application\DTOS\Employee\EmployeeDTO;

class CreateEmployeeRequest extends BaseRequestAbstract
{
    protected  $dtoClass = EmployeeDTO::class;
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
            'translations' => 'nullable|array',
            'name' => 'required|string',
            'username' => 'nullable|string|unique:employees,username',
            'phone' => 'required|string|unique:employees,phone',
            'identify_number' => 'nullable|string|unique:employees,id_number',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string',
            'image' => 'nullable|string',
            'cover_image' => 'nullable|string',
            'real_video' => 'nullable|string',
            'role' => 'required|integer|in:' . implode(',', array_map(fn($type) => $type->value, EmployeeTypeEnum::cases())),
            'socials' => 'nullable|array',
            'socials.facebook' => 'nullable|string',
            'socials.twitter' => 'nullable|string',
            'socials.instagram' => 'nullable|string',
            'socials.linkedin' => 'nullable|string',
            'socials.tiktok' => 'nullable|string',
            'socials.whatsapp' => 'nullable|string',
            'certificates' => 'nullable|array',
            'certificates.*.translations' => 'required|array',
            'certificates.*.link' => 'nullable|string',
            'certificates.*.image' => 'nullable|string',
            'certificates.*.is_website' => 'required|boolean',
            'subject_stage_ids' => 'nullable|array',
        ];
    }
}
