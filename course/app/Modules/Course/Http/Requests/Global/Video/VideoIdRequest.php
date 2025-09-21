<?php

namespace App\Modules\Course\Http\Requests\Global\Video;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Video\VideoFilterDTO;

class VideoIdRequest extends BaseRequestAbstract
{
    protected $dtoClass = VideoFilterDTO::class;
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
            'video_id' => 'required|integer|exists:videos,id',
        ];
    }


}
