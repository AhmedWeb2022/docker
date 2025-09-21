<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Faq;

use App\Modules\Diploma\Application\DTOS\Faq\FaqDTO;
use App\Modules\Diploma\Application\DTOS\Privacy\PrivacyDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Application\DTOS\Base\BaseDTO;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateFaqRequest extends BaseRequestAbstract
{
    protected $dtoClass = FaqDTO::class;
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
        $rules = [];
        $rules['faq_id'] = ['required', 'integer', 'exists:faqs,id'];
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $rules["translations.answer_$locale"] = 'nullable|string';
            $rules["translations.question_$locale"] = 'nullable|string';
        }
        $rules['is_active'] = 'nullable|boolean';
        $rules['order'] = 'nullable|integer';
        $rules['diploma_id'] = 'nullable|integer|exists:diplomas,id';
        return $rules;
    }
}
