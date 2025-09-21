<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Course;

use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\Enums\Setting\SettingStatusEnum;
use App\Modules\Course\Application\Enums\Setting\SettingWatchVideoEnum;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Course\Application\DTOS\Course\CourseDTO;
use Illuminate\Validation\Rules\Enum;

class UpdateCourseRequest extends BaseRequestAbstract
{
    protected $dtoClass = CourseDTO::class;
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
            "course_id" => "required|integer|exists:courses,id",
            'translations' => 'nullable|array',
            'code' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer|exists:courses,id',
            'certificate_id' => 'nullable',
            'partner_id' => 'nullable',
            'type' => 'nullable',
            'is_free' => 'nullable|boolean',
            'status' => 'nullable|string|in:active,inactive',
            'image' => 'nullable',
            'is_free' => 'nullable|boolean',
            'level_type' => 'nullable|integer|in:1,2,3',
            'video' => 'nullable|array',
            'video.is_file' => 'nullable|in:0,1',
            'video.video_type' => 'nullable',
            'video.file' => 'required_if:video.is_file,1',
            'video.link' => 'required_if:video.is_file,0',
            "setting" => "nullable",
            "setting.code_status" => ['nullable', new Enum(SettingStatusEnum::class)],
            "setting.is_security" => "nullable|boolean",
            "setting.is_watermark" => "nullable|boolean",
            "setting.is_voice" => "nullable|boolean",
            "setting.is_emulator" => "nullable|boolean",
            "setting.time_number" => "nullable",
            "setting.number_of_voice" => "nullable",
            "setting.watch_video" => ["nullable", new Enum(SettingWatchVideoEnum::class)],
            "setting.number_watch_video" => "nullable",
            'subject_stage_ids' => 'nullable|array',

        ];
    }
}
