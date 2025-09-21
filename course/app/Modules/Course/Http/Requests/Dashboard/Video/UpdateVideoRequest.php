<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Video;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;

class UpdateVideoRequest extends BaseRequestAbstract
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
    public function customRules(): array
    {
        return [ // data validation
            'course_video_id' => 'required|integer|exists:course_videos,id',
            'course_id' => 'nullable|integer|exists:courses,id',
            'type' => 'required',
            'video' => 'nullable',
            'video_type' => 'nullable|string',
            'path' => 'nullable',
        ];
    }
}
