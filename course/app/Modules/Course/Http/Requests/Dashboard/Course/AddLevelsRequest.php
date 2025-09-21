<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Course;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Course\AddLevelDTO;

class AddLevelsRequest extends BaseRequestAbstract
{
    protected $dtoClass = AddLevelDTO::class;
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
            'course_id' => 'required|integer|exists:courses,id',
            'level_ids' => 'required|array',
            'level_ids.*' => 'required|integer|exists:levels,id',
            'price' => 'required|numeric',
            'payment_status' => 'required|integer|in:1,2,3',
        ];
    }
}
