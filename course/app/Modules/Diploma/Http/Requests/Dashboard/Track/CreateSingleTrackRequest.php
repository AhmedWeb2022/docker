<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Track;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Base\Http\Rules\ImageBase64OrFileRule;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreateSingleTrackRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaLevelTrackDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function customRules(): array
    {
        $rules =  [ // data validation
            'diploma_id' => 'required|integer|exists:diplomas,id',
            'translations' => 'required|array',
            'image' => ['nullable', new ImageBase64OrFileRule()],
            'diploma_level_id' => ['nullable', 'integer', Rule::exists('diploma_levels', 'id')->where('diploma_id', $this->input('diploma_id'))],
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ];
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $locale) {
            $rules["translations.title_$locale"] = [
                'required',
                'string',
                'min:2',
                'max:255',
            ];
            $rules["translations.description_$locale"] = 'nullable|string|max:1000';
        }

        $rules['courses_ids'] = 'required|array';
        $rules['courses_ids.*'] = 'required|integer|exists:courses,id';
        
        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {
            $diplomaId = $this->input('diploma_id');

            $exists = DB::table('diplomas')
                ->where('id', $diplomaId)
                ->first();

            if ( $exists->has_track == false) {
                $validator->errors()->add('diploma_id', 'The selected diploma is not valid. please make sure it has a track.');
            }elseif ($exists->has_level == true) {
                $validator->errors()->add('diploma_id', 'The selected diploma already has a level. You cannot create a track for it.');
            }
        });

    }
}
