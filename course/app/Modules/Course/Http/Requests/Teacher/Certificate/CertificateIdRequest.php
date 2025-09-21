<?php

namespace App\Modules\Course\Http\Requests\Teacher\Certificate;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Teacher\Certificate\CertificateFilterDTO;

class CertificateIdRequest extends BaseRequestAbstract
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
            'certificate_id' => 'required|integer|exists:certificates,id',
        ];
    }
}
