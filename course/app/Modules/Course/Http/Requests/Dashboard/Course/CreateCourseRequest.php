<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Course;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;

use App\Modules\Course\Application\DTOS\Course\CourseDTO;
use App\Modules\Course\Application\Enums\Course\CourseTypeEnum;
use App\Modules\Course\Application\Enums\Setting\SettingStatusEnum;
use App\Modules\Course\Application\Enums\Video\VideoIsFileTypeEnum;
use App\Modules\Course\Application\Enums\Course\CourseLevelTypeEnum;
use App\Modules\Course\Application\Enums\Setting\SettingWatchVideoEnum;
use App\Modules\Course\Application\Enums\Course\CourseEducationTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Stage\StageApiService;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;

class CreateCourseRequest extends BaseRequestAbstract
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
    public function CustomRules(): array
    {
        // dd( CourseLevelTypeEnum::values());
        return [ // data validation
            'translations' => 'required|array',
            'translations.title_ar' => 'required|string',
            'translations.title_en' => 'required|string',
            "translations.description_ar" => "nullable|string",
            "translations.description_en" => "nullable|string",
            "translations.card_description_ar" => "nullable|string",
            "translations.card_description_en" => "nullable|string",
            'parent_id' => 'nullable|integer|exists:courses,id',
            'organization_id' => 'nullable',
            // 'stage_id' => 'nullable',
            'level_type' => 'required|integer|in:' . implode(',', CourseLevelTypeEnum::values()),
            'is_free' => 'nullable|boolean',
            // 'subject_id' => 'nullable',
            'certificate_id' => 'nullable|exists:certificates,id',
            'partner_id' => 'nullable|exists:partners,id',
            'type' => 'nullable',
            'status' => 'nullable|string|in:active,inactive',
            'is_private' => 'nullable|boolean',
            'has_website' => 'nullable|boolean',
            'has_app' => 'nullable|boolean',
            'start_date' => 'nullable|date|date_format:Y-m-d|before_or_equal:end_date',
            'end_date'   => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',

            'image' => 'nullable',
            'video' => 'nullable|array',
            'video.is_file' => 'nullable|in:' . implode(',', VideoIsFileTypeEnum::values()),
            'video.video_type' => 'nullable',
            'video.file' => 'required_if:video.is_file,' . VideoIsFileTypeEnum::FILE->value,
            'video.link' => 'required_if:video.is_file,' . VideoIsFileTypeEnum::NOT_FILE->value,
            "setting" => "nullable",
            "setting.code_status" => 'nullable|integer|in:' . implode(',', SettingStatusEnum::values()),
            "setting.is_security" => "nullable|boolean",
            "setting.is_watermark" => "nullable|boolean",
            "setting.is_voice" => "nullable|boolean",
            "setting.is_emulator" => "nullable|boolean",
            "setting.time_number" => "nullable|numeric",
            "setting.number_of_voice" => "nullable|numeric",
            "setting.watch_video" => 'nullable|integer|in:' . implode(',', SettingWatchVideoEnum::values()),
            "setting.number_watch_video" => "nullable|numeric",
            "platforms" => "nullable|array",
            "platforms.*.id" => "nullable|exists:platforms,id",
            "platforms.*.link" => "nullable|url",
            "payment" => "nullable|array",
            "payment.is_paid" => "nullable|boolean",
            "payment.price" => "nullable|numeric",
            "payment.currency_id" => "nullable|integer|exists:currencies,id",
            "contain_live" => "nullable|boolean",
            "is_certificate" => "nullable|boolean",
            "course_type" => 'required|integer|in:' . implode(',', CourseTypeEnum::values()),
            "education_type" => 'required|integer|in:' . implode(',', CourseEducationTypeEnum::values()),
            "teacherIds" => "nullable|array",
            "teacherIds.*" => [
                'nullable',
                'integer',
            ],
            "subject_stage_ids" => "nullable|array",
            "has_discount" => "nullable|boolean",
            "discount_amount" => "nullable|numeric",
            "discount_from_date" => 'nullable|date|date_format:Y-m-d|before_or_equal:discount_to_date',
            "discount_to_date" => 'nullable|date|date_format:Y-m-d|after_or_equal:discount_from_date',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $image = $this->input('image');
            $file = $this->file('image');
            $stage_id = $this->input('stage_id');
            $subject_id = $this->input('subject_id');
            $teacher_ids = $this->input('teacherIds');
            $stageApiService = new StageApiService();
            $employeeApiService = new EmployeeApiService();

            // ============================  Image Validate ============================
            if ($image && !$file && !is_string($image)) {
                $validator->errors()->add('image', 'The image must be a valid string (e.g., URL or base64) or an image file.');
            }

            if ($file && !$file->isValid()) {
                $validator->errors()->add('image', 'The uploaded image is invalid.');
            }

            if ($file && !in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'])) {
                $validator->errors()->add('image', 'The image must be a file of type: jpeg, png, jpg, gif, webp.');
            }
            // ============================  Image Validate ============================

            // if ($stage_id) {
            //     $resopnse = $stageApiService->checkStageExist($stage_id);
            //     // dd($resopnse);
            //     if ((isset($resopnse['status']) && $resopnse['status'] == false) || (isset($resopnse['success']) && $resopnse['success'] == false)) {
            //         $validator->errors()->add('stage_id', $resopnse['errors'][0]);
            //     }
            // }

            // if ($subject_id) {
            //     $resopnse = $stageApiService->checkSubjectExist($subject_id);
            //     // dd($resopnse);
            //     if (isset($resopnse['status']) && $resopnse['status'] == false || (isset($resopnse['success']) && $resopnse['success'] == false)) {
            //         $validator->errors()->add('subject_id', $resopnse['errors'][0]);
            //     }
            // }

            if ($teacher_ids && count($teacher_ids) > 0) {
                $resopnse = $employeeApiService->checkEmployeeExist($teacher_ids);
                // dd($resopnse);
                if (isset($resopnse['status']) && $resopnse['status'] == false || (isset($resopnse['success']) && $resopnse['success'] == false)) {
                    $validator->errors()->add('teacherIds', $resopnse['errors'][0] ?? $resopnse['message'] ?? 'Teacher not found');
                }
            }

            // Discount validation
            if ($this->input('has_discount') == true) {
                if (empty($this->input('discount_amount'))) {
                    $validator->errors()->add('discount_amount', 'The discount amount is required when has discount is true.');
                }
                if (empty($this->input('discount_from_date'))) {
                    $validator->errors()->add('discount_from_date', 'The discount from date is required when has discount is true.');
                }
                if (empty($this->input('discount_to_date'))) {
                    $validator->errors()->add('discount_to_date', 'The discount to date is required when has discount is true.');
                }

                if ($this->input('discount_from_date') && $this->input('discount_to_date')) {
                    if ($this->input('discount_from_date') > $this->input('discount_to_date')) {
                        $validator->errors()->add('discount_from_date', 'The discount from date must be before or equal to the discount to date.');
                    }
                }
            }
        });
    }
}
