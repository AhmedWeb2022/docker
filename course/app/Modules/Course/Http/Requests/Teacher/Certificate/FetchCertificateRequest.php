<?php

namespace App\Modules\Course\Http\Requests\Teacher\Certificate;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Certificate\CertificateFilterDTO;

class FetchCertificateRequest extends BaseRequestAbstract
{
    protected $dtoClass = CertificateFilterDTO::class;
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
            'translations' => 'nullable|array',
            'partner_id' => 'nullable|integer|exists:partners,id',
            'is_website' => 'nullable|boolean',
            'employee_id' => 'nullable|integer',
            'word'=>'nullable|string',
        ];
    }
}
