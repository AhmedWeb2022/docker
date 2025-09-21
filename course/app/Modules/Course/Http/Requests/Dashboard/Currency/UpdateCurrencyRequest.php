<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Currency;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Currency\CurrencyDTO;
use App\Modules\Course\Application\DTOS\Partner\PartnerDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyRequest extends BaseRequestAbstract
{
    protected $dtoClass = CurrencyDTO::class;
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
            'translations' => 'nullable|array',
            'code' => 'nullable|string',
            'currency_id' => 'required|integer|exists:currencies,id',
        ];
    }
}
