<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Certificate;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Certificate\CertificateFilterDTO;

class FetchCertificateResourceRequest extends BaseRequestAbstract
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
            'employee_id' => 'required|integer',
        ];
    }
}
