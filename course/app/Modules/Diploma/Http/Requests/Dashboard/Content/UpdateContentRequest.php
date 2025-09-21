<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Content;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaContent\DiplomaContentDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContentRequest extends BaseRequestAbstract
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
            'diploma_level_id' => ['sometimes', 'integer', Rule::exists('diploma_levels', 'id')->where('diploma_id', $this->input('diploma_id'))],
            'track_id'=> 'sometimes|integer|exists:diploma_level_tracks,id',
            'diploma_id' => 'sometimes|integer|exists:diplomas,id',
        ];
    }
}
