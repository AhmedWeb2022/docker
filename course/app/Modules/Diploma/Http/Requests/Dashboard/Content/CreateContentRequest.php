<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Content;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\DiplomaContent\DiplomaContentDTO;
use Doctrine\Inflector\Rules\English\Rules;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateContentRequest extends BaseRequestAbstract
{
    protected $dtoClass = DiplomaContentDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function customRules(): array
    {
        return [
            'diploma_level_id' => ['nullable', 'integer', Rule::exists('diploma_levels', 'id')->where('diploma_id', $this->input('diploma_id'))],
            'diploma_id' => 'required|integer|exists:diplomas,id',
            'track_id' => 'nullable|integer|exists:diploma_level_tracks,id',
            'order'=> 'nullable|integer',
        ];
    }

}
