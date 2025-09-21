<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Track;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Diploma\Application\DTOS\Level\LevelDTO;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateTrackRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaLevelTrackDTO::class;
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
        $rules =  [ // data validation
            'track_id' => 'required|integer|exists:diploma_level_tracks,id',
            'diploma_id' => 'sometimes|integer|exists:diplomas,id',
            'translations' => 'sometimes|array',
            'image' => ['nullable', new ImageBase64OrFileRule()],
            'diploma_level_id' => ['nullable', 'integer', Rule::exists('diploma_levels', 'id')->where('diploma_id', $this->input('diploma_id'))],
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $rules["translations.title_$locale"] = [
                'sometimes',
                'string',
                'min:2',
                'max:255',
            ];
            $rules["translations.description_$locale"] = 'sometimes|string|max:1000';
        }
        $rules['courses_ids'] = 'sometimes|array';
        $rules['courses_ids.*'] = 'sometimes|integer|exists:courses,id';
        return $rules;
    }
}
