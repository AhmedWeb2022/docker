<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Content;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaContent\DiplomaContentDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use Illuminate\Foundation\Http\FormRequest;

class FilterContentRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaContentDTO::class;
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
            'diploma_id' => 'required|integer|exists:diplomas,id',
        ];
    }
}
