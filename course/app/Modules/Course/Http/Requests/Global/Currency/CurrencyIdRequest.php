<?php

namespace App\Modules\Course\Http\Requests\Global\Currency;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Currency\CurrencyFilterDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Course\CourseFilterDTO;

class CurrencyIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = CurrencyFilterDTO::class;
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
            'currency_id' => 'required|integer|exists:currencies,id',
        ];
    }


}
