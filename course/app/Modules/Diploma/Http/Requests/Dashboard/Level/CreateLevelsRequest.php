<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Level;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Diploma\Application\DTOS\Diploma\AddLevelDTO;
use App\Modules\Diploma\Application\DTOS\Level\LevelDTO;
use Illuminate\Foundation\Http\FormRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreateLevelsRequest extends BaseRequestAbstract
{
    protected $dtoClass = AddLevelDTO::class;
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
        $rules ['diploma_id'] = 'required|integer|exists:diplomas,id';
        $rules['levels'] = 'required|array';
        $rules['levels.*.translations'] = 'required|array';
        $rules['levels.*.is_active'] = 'nullable|boolean';
        $rules['levels.*.order'] = 'nullable|integer';
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $rules["levels.*.translations.title_$locale"] = [
                'required',
                'string',
                'min:2',
                'max:255',
            ];
            $rules["levels.*.translations.description_$locale"] = 'nullable|string|max:1000';
        }
        $rules['levels.*.image'] = ['nullable', new ImageBase64OrFileRule()];
        $rules['levels.*.has_track'] = 'nullable|boolean';
        $rules['levels.*.tracks'] = 'nullable|array';
        /* tracks validation  start*/

        $rules['levels.*.tracks.*.translations'] = 'required|array';
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $rules["levels.*.tracks.*.translations.title_$locale"] = 'nullable|required_if:has_track,true|string|max:255';
            $rules["levels.*.tracks.*.translations.description_$locale"] = 'nullable|string|max:1000';
        }
        // $rules['tracks.*.contents'] = 'nullable|array';
        $rules['levels.*.tracks.*.courses_ids'] = 'nullable|required_if:has_track,true|array';
        $rules['levels.*.tracks.*.courses_ids.*'] = 'required|integer|exists:courses,id';

        /* tracks validation  end*/

        //if level->has_track is false, then courses_ids is required
        $rules['levels.*.courses_ids'] = 'nullable|required_if:has_track,false|array';
        $rules['levels.*.courses_ids.*'] = 'required|integer|exists:courses,id';

        return $rules;
    }
}
