<?php

namespace App\Modules\Diploma\Http\Requests\Dashboard\Level;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Diploma\Application\DTOS\Level\LevelFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class FilterLevelRequest extends BaseRequestAbstract
{
    protected $dtoClass = LevelFilterDTO::class;
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
