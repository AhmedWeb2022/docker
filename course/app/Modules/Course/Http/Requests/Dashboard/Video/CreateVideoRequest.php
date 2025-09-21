<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Video;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends BaseRequestAbstract
{
    protected $dtoClass = VideoDTO::class;
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
            'course_id' => 'nullable|integer|exists:courses,id',
            'type' => 'required|in:0,1',
            'video_type' => 'nullable|string',
            'video' => 'required_if:type,0',
            'path' => 'required_if:type,1',
        ];
    }
}
