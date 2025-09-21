<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Level;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Level\LevelDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateLevelRequest extends BaseRequestAbstract
{
    protected $dtoClass = LevelDTO::class;
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
    public function CustomRules(): array
    {
        return [ // data validation
            'translations' => 'required|array',
            'image' => 'required',
            'is_standalone' => 'required|integer|in:0,1',
            'parent_id' => 'required_if:is_standalone,0|integer|exists:levels,id',
        ];
    }
}
