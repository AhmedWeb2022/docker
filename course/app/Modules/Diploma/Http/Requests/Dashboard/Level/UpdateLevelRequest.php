<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Level;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\Diploma\AddLevelDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevel\DiplomaLevelDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Diploma\Application\DTOS\Level\LevelDTO;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateLevelRequest extends BaseRequestAbstract
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
        $rules = [ // data validation
            'level_id' => 'required|integer|exists:diploma_levels,id',
            'translations' => 'sometimes|array',
            'image' => 'sometimes|nullable',
            'has_track' => 'required|boolean',
            'tracks' => 'required_if:has_track,true|array',
        ];
        $rules['tracks.*.translations'] = 'required|array';
        foreach (LaravelLocalization::getSupportedLocales() as $locale => $supportedLocale) {
            $rules["tracks.*.translations.title_$locale"] = 'required_if:has_track,true|string|max:255';
            $rules["tracks.*.translations.description_$locale"] = 'nullable|string|max:1000';
        }
        $rules['tracks.*.courses_ids'] = 'nullable|required_if:has_track,true|array';
        $rules['tracks.*.courses_ids.*'] = 'required|integer|exists:courses,id';

        //if level->has_track is false, then courses_ids is required
        $rules['courses_ids'] = 'nullable|required_if:has_track,false|array';
        $rules['courses_ids.*'] = 'required|integer|exists:courses,id';
        return $rules;

    }
}
