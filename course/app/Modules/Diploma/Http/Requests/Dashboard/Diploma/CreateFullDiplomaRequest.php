<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Diploma;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaDTO;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;

class CreateFullDiplomaRequest extends BaseRequestAbstract
{

    protected $dtoClass = DiplomaDTO::class;

    public function authorize(): bool
    {
        return true; // أو logic حسب النظام عندك
    }

    public function rules(): array
    {
        return $this->customRules();
    }

    public function customRules(): array
    {
        $rules = [
            'translations' => 'required|array',

            'main_image' => ['nullable', new ImageBase64OrFileRule()],
            'has_level' => 'nullable|boolean',
            'has_track' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'language' => ['nullable', Rule::in(Diploma::LANGUAGES)],
            'diploma_specialization' => 'nullable|string|max:255',
            'target' => 'nullable|string|max:255',
            // 'targets' => 'nullable|array',
            // 'targets.*' => 'string|max:255',
            // 'abouts' => 'nullable|array',
            // 'abouts.*' => 'string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',

            'levels' => 'nullable|array',
            'levels.*.translations' => 'required_with:levels|array',
            'levels.*.has_track' => 'nullable|boolean',
            'levels.*.image' => ['nullable', new ImageBase64OrFileRule()],
            'levels.*.tracks' => 'nullable|array',

            // تنبيه: required_if هنا قد لا تعمل بشكل صحيح بسبب استخدام wildcard
            'levels.*.courses_ids' => 'nullable|array',
            'levels.*.courses_ids.*' => 'required|integer|exists:courses,id',

            'levels.*.tracks.*.courses_ids' => 'nullable|array',
            'levels.*.tracks.*.courses_ids.*' => 'required|integer|exists:courses,id',
            'levels.*.tracks.*.translations' => 'required_with:levels.*.tracks|array',

            'tracks' => 'nullable|array',
            'tracks.*.translations' => 'required_with:tracks|array',
            'tracks.*.courses_ids' => 'required_with:tracks|array',
            'tracks.*.courses_ids.*' => 'required|integer|exists:courses,id',
            'tracks.*.image' => ['nullable', new ImageBase64OrFileRule()],
        ];


        // المسارات مباشرة بدون مستويات
        $rules['tracks'] = 'nullable|array';
        $rules['tracks.*.translations'] = 'required_with:tracks|array';
        $rules['tracks.*.courses_ids'] = 'required_with:tracks|array';
        $rules['tracks.*.courses_ids.*'] = 'required|integer|exists:courses,id';

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
