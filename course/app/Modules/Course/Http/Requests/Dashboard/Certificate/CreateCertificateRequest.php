<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Certificate;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Certificate\CertificateDTO;
use Illuminate\Foundation\Http\FormRequest;

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

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $partner_id = $this->get('partner_id');
    //         $is_website = $this->get('is_website');
    //         if ($is_website) {
    //             if ($partner_id) {
    //                 $validator->errors()->add('partner_id', 'Partner ID should not be provided when is_website is true.');
    //             }
    //         }
    //     });
    // }
}
