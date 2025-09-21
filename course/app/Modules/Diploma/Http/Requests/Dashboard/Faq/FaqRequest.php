<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Faq;

use App\Modules\Diploma\Application\DTOS\Faq\FaqDTO;
use App\Modules\Diploma\Application\DTOS\Privacy\PrivacyDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Application\DTOS\Base\BaseDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

class FaqRequest extends BaseRequestAbstract
{
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
    public function rules(): array
    {
        return [ // data validation
            'faq_id' => 'nullable|integer|exists:faq,id',
            'diploma_id' => 'nullable|integer|exists:diplomas,id',
        ];
    }
}
