<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Track;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaLevel\DiplomaLevelDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Diploma\Application\DTOS\Level\LevelFilterDTO;
use Illuminate\Validation\Rule;

class FetchTrackRequest extends BaseRequestAbstract
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
        return [ // data validation
            'diploma_level_id' => ['nullable', 'integer', Rule::exists('diploma_levels', 'id')->where('diploma_id', $this->input('diploma_id'))],
            'diploma_id'=> 'nullable|integer|exists:diplomas,id',
            'translations' => 'nullable|array',
        ];
    }
}
