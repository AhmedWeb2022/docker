<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Diploma;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaDTO;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreateDiplomaRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaDTO::class;
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
        $rules =  [ // data validation
            'translations' => 'required|array',
            'translations.title_ar' => 'required|string',
            'translations.title_en' => 'required|string',
            "translations.short_description_ar" => "nullable|string",
            "translations.short_description_en" => "nullable|string",
            "translations.full_description_ar" => "nullable|string",
            "translations.full_description_en" => "nullable|string",
            'main_image' => ['nullable', new ImageBase64OrFileRule()],
            'diploma_specialization' => 'nullable|string|max:255',

            'has_level' => 'nullable|boolean',
            'has_track' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'target' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'language' => [
                'nullable',
                'string',
                'max:11',
                // Rule::in(Diploma::LANGUAGES)
            ],
            // 'targets' => 'nullable|array',
            // 'targets.*' => 'string|max:255',
            // 'abouts' => 'nullable|array',
            // 'abouts.*' => 'string|max:255',
        ];
        $rules['targets'] = 'nullable|array';
        $rules['targets.*.translations'] = 'required_with:targets|array';
        // $rules['targets.7omsa'] = 'required_with:targets|string|min:2|max:255';
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $rules["targets.*.translations.title_$locale"] = [
                'required_with:targets',
                'string',
                'min:2',
                'max:255',
            ];
            $rules["targets.*.translations.description_$locale"] = 'nullable|string';
        }
        $rules['abouts'] = 'nullable|array';
        $rules['abouts.*.translations'] = 'required_with:abouts|array';
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $rules["abouts.*.translations.title_$locale"] = [
                'required_with:abouts',
                'string',
                'min:2',
                'max:255',
            ];
            $rules["abouts.*.translations.description_$locale"] = 'nullable|string';
        }
        return $rules;
    }
}
