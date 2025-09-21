<?php

namespace App\Modules\Diploma\Http\Requests\Global\Diploma;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaFilterDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevel\DiplomaLevelDTO;

class TrackIdRequest extends BaseRequestAbstract
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
            'track_id' => 'required|integer|exists:diploma_level_tracks,id',
        ];
    }


}
