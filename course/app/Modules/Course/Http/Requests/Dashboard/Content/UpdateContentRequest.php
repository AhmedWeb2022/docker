<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Content;

use Illuminate\Validation\Rule;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Content\ContentDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use App\Modules\Course\Infrastructure\Persistence\Models\Group\Group;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use Illuminate\Validation\Validator as LaravelValidator;

class UpdateContentRequest extends BaseRequestAbstract
{
    protected $dtoClass = ContentDTO::class;

    public function authorize(): bool
    {
        return true;
    }

    public function CustomRules(): array
    {
        return [ // data validation
            'content_id' => 'required|integer|exists:contents,id',
            'translations' => 'nullable|array',
            'course_id' => 'nullable|integer|exists:courses,id',
            'lesson_id' => 'nullable|integer|exists:lessons,id',
            'parent_id' => 'nullable|integer|exists:contents,id',
            'organization_id' => 'nullable',
            'type' => 'prohibited',
            'status' => 'nullable',
            'image' => 'nullable',
            'can_skip' => 'nullable|integer',
            'skip_rate' => 'nullable|integer',

            // content
            'content' => 'nullable',
            'content.translations' => 'nullable|array',

            'content.image' => 'nullable',
            'content.is_file' => 'nullable',
            'content.session_type' => 'nullable',
            'content.audio_type' => 'nullable',
            'content.document_type' => 'nullable',
            'content.answers' => 'nullable|array',
            'content.answers.*.translations' => 'nullable|array',

            'content.file' => [
                'required_if:content.is_file,1',
                function ($attribute, $value, $fail) {
                    $type = request('type');

                    if ($type == ContentTypeEnum::SESSION->value && !str_starts_with($value, 'data:video/')) {
                        $fail('The uploaded file must be a video.');
                    }

                    if ($type == ContentTypeEnum::AUDIO->value && !str_starts_with($value, 'data:audio/')) {
                        $fail('The uploaded file must be an audio file.');
                    }

                    if ($type == ContentTypeEnum::DOCUMENT->value && !str_starts_with($value, 'data:application/')) {
                        $fail('The uploaded file must be a document.');
                    }
                },
            ],
            'content.link' => 'nullable',


            // Live
            'content.group_id' => 'nullable|exists:groups,id',
            'content.teacher_id' => 'nullable',
            // 'content.course_id' => 'nullable|exists:courses,id',
            'content.start_date' => 'nullable',
            'content.end_date' => 'nullable',
            'content.start_time' => 'nullable',
            'content.end_time' => 'nullable|after:content.start_time',
            // referances
            'referances' => 'nullable|array',


        ];
    }

    // public function withValidator(LaravelValidator $validator): void
    // {
    //     $validator->after(function ($validator) {
    //         $content = $this->input('content', []);
    //         $type = $content['type'] ?? null;

    //         $file = $content['file'] ?? null;
    //         $link = $content['link'] ?? null;
    //         $isFile = $content['is_file'] ?? null;
    //         $answers = $content['answers'] ?? null;
    //         $can_skip = $content['can_skip'] ?? null;
    //         $skip_rate = $content['skip_rate'] ?? null;
    //         $audio_type = $content['audio_type'] ?? null;
    //         $document_type = $content['document_type'] ?? null;
    //         $session_type = $content['session_type'] ?? null;

    //         $actualType = $type ?? $this->route('type');

    //         // Poll Type
    //         if ($actualType === ContentTypeEnum::POLL->value) {
    //             if (!is_null($answers) && (!is_array($answers) || count($answers) < 1)) {
    //                 $validator->errors()->add('content.answers', 'Answers must be a non-empty array.');
    //             }

    //             if (!is_null($link)) $validator->errors()->add('content.link', 'Link must not be sent for Poll type.');
    //             if (!is_null($isFile)) $validator->errors()->add('content.is_file', 'is_file must not be sent for Poll type.');
    //             if (!is_null($file)) $validator->errors()->add('content.file', 'File must not be sent for Poll type.');
    //             if (!is_null($can_skip)) $validator->errors()->add('content.can_skip', 'can_skip must not be sent for Poll type.');
    //             if (!is_null($skip_rate)) $validator->errors()->add('content.skip_rate', 'skip_rate must not be sent for Poll type.');
    //             if (!is_null($audio_type)) $validator->errors()->add('content.audio_type', 'audio_type must not be sent for Poll type.');
    //             if (!is_null($document_type)) $validator->errors()->add('content.document_type', 'document_type must not be sent for Poll type.');
    //             if (!is_null($session_type)) $validator->errors()->add('content.session_type', 'session_type must not be sent for Poll type.');
    //         } else {
    //             if (!is_null($answers)) {
    //                 $validator->errors()->add('content.answers', 'Answers must not be sent unless type is Poll.');
    //             }
    //         }

    //         // File Format Validation (if file is sent)
    //         if (!is_null($file)) {
    //             $expectedPrefix = match ($actualType) {
    //                 ContentTypeEnum::SESSION->value => 'data:video/',
    //                 ContentTypeEnum::AUDIO->value => 'data:audio/',
    //                 ContentTypeEnum::DOCUMENT->value => 'data:application/',
    //                 default => '',
    //             };

    //             if ($expectedPrefix && !str_starts_with($file, $expectedPrefix)) {
    //                 $validator->errors()->add('content.file', "Invalid file format for this content type.");
    //             }
    //         }

    //         // Link Validation (if link is sent)
    //         if (!is_null($link) && !filter_var($link, FILTER_VALIDATE_URL)) {
    //             $validator->errors()->add('content.link', 'Link must be a valid URL.');
    //         }

    //         // session_type only allowed for SESSION type
    //         if (!is_null($session_type) && $actualType !== ContentTypeEnum::SESSION->value) {
    //             $validator->errors()->add('content.session_type', 'session_type is only allowed for SESSION type.');
    //         }

    //         // audio_type only allowed for AUDIO type
    //         if (!is_null($audio_type) && $actualType !== ContentTypeEnum::AUDIO->value) {
    //             $validator->errors()->add('content.audio_type', 'audio_type is only allowed for AUDIO type.');
    //         }

    //         // document_type only allowed for DOCUMENT type
    //         if (!is_null($document_type) && $actualType !== ContentTypeEnum::DOCUMENT->value) {
    //             $validator->errors()->add('content.document_type', 'document_type is only allowed for DOCUMENT type.');
    //         }

    //         // is_file logic: if both file/link are sent, validate them based on is_file (optional)
    //         if (!is_null($isFile)) {
    //             if ($isFile) {
    //                 if (!is_null($link)) {
    //                     $validator->errors()->add('content.link', 'Link must not be sent when is_file is 1.');
    //                 }
    //             } else {
    //                 if (!is_null($file)) {
    //                     $validator->errors()->add('content.file', 'File must not be sent when is_file is 0.');
    //                 }
    //             }
    //         }
    //     });
    // }
}
