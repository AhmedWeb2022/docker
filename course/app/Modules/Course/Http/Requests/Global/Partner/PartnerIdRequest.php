<?php

namespace App\Modules\Course\Http\Requests\Global\Partner;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Partner\PartnerFilterDTO;

class PartnerIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = PartnerFilterDTO::class;
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
            'partner_id' => 'required|integer|exists:partners,id',
        ];
    }
}
