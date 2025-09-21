<?php

namespace App\Modules\Course\Http\Requests\Teacher\Certificate;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Certificate\CertificateDTO;

class CreateCertificateRequest extends BaseRequestAbstract
{
    protected $dtoClass = CertificateDTO::class;
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
    public function CustomRules(): array
    {
        return [ // data validation
            'translations' => 'required|array',
            'image' => 'required',
            'link' => 'nullable',
            'partner_id' => 'nullable|integer|exists:partners,id',
            'is_website' => 'required|boolean',
            'employee_id' => 'nullable|integer'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $employee_id = $this->input('employee_id');
            if (!$employee_id) {
                $employee_id = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::TEACHER->value);
            }
        });
    }
}
