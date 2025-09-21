<?php

namespace App\Modules\Course\Http\Requests\Api\Rate;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Rate\RateDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateRateRequest extends BaseRequestAbstract
{
    protected $dtoClass = RateDTO::class;
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
            'rate' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'course_id' => 'nullable|required_without:diploma_id|exists:courses,id',
            'diploma_id' => 'nullable|required_without:course_id|exists:diplomas,id',
            'user_id' => 'nullable|integer',
        ];
    }
}
