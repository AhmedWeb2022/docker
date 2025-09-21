<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Level;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Level\LevelDTO;

class UpdateLevelRequest extends BaseRequestAbstract
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
    public function customRules(): array
    {
        return [ // data validation
            'level_id' => 'required|integer|exists:levels,id',
            'translations' => 'required|array',
            'image' => 'required',
            'parent_id' => 'nullable|integer|exists:levels,id',
            'is_standalone' => 'required|integer|in:0,1',
        ];
    }
}
