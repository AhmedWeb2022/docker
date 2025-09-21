<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Diploma;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateDiplomaRequest extends BaseRequestAbstract
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
    public function customRules(): array
    {
        $rules= [ // data validation
            'diploma_id' => 'required|exists:diplomas,id',
            'main_image' => 'nullable',
            'has_level' => 'nullable|boolean',
            'has_track' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'target' => 'nullable|string|max:255',
            'translations' => 'nullable|array',
            'diploma_specialization' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
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
