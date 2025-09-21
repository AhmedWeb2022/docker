<?php

namespace App\Modules\Course\Http\Requests\Dashboard\Content;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Modules\Base\Domain\Request\BaseRequestAbstract;
use App\Modules\Course\Application\DTOS\Content\ContentDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Application\Trait\Content\HandleContentRequestTrait;
use App\Modules\Course\Infrastructure\Persistence\Models\Group\Group;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class CreateContentRequest extends BaseRequestAbstract
{
    use HandleContentRequestTrait;
    protected $dtoClass = ContentDTO::class;
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
            'translations' => 'nullable|array',
            'course_id' => 'required|integer|exists:courses,id',
            'lesson_id' => 'nullable|integer|exists:lessons,id',
            'parent_id' => 'nullable|integer|exists:contents,id',
            'organization_id' => 'nullable',
            'type' => 'required',
            'status' => 'nullable',
            'image' => 'nullable',
            'can_skip' => 'nullable|integer',
            'skip_rate' => 'nullable|integer',

            // content
            'content' => [
                Rule::requiredIf(function () {
                    return request('type') !== ContentTypeEnum::EXAM->value;
                }),
            ],
            'content.translations' => Rule::requiredIf(function () {
                return request('type') !== ContentTypeEnum::EXAM->value
                    && request('type') !== ContentTypeEnum::QUESTION->value;
            }),


            'content.image' => 'nullable',
            'content.is_file' => 'required_if:type,' . ContentTypeEnum::SESSION->value . ',' . ContentTypeEnum::AUDIO->value,
            // 'content.session_type' => 'required_if:type,' . ContentTypeEnum::SESSION->value,
            // 'content.audio_type' => 'required_if:type,' . ContentTypeEnum::AUDIO->value,
            // 'content.document_type' => 'required_if:type,' . ContentTypeEnum::DOCUMENT->value,
            'content.answers' => 'required_if:type,' . ContentTypeEnum::POLL->value . '|array',
            'content.answers.*.translations' => 'required_if:type,' . ContentTypeEnum::POLL->value . '|array',

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
            // 'content.can_skip' => 'nullable|integer',
            // 'content.skip_rate' => 'required_if:content.can_skip,1|integer',

            // Live
            'content.group_id' => 'required_if:type,' . ContentTypeEnum::LIVE->value . '|exists:groups,id',
            'content.teacher_id' => 'required_if:type,' . ContentTypeEnum::LIVE->value,
            // 'content.course_id' => 'required_if:type,' . ContentTypeEnum::LIVE->value . '|exists:courses,id',
            'content.start_date' => 'required_if:type,' . ContentTypeEnum::LIVE->value,
            'content.end_date' => 'required_if:type,' . ContentTypeEnum::LIVE->value,
            'content.start_time' => 'required_if:type,' . ContentTypeEnum::LIVE->value,
            'content.end_time' => 'required_if:type,' . ContentTypeEnum::LIVE->value . '|after:content.start_time',
            // referances
            'referances' => 'nullable|array',


            // questions

            "content.question_type" => "required_if:type," . ContentTypeEnum::QUESTION->value,
            "content.identicality" => "nullable",
            "content.identicality_percentage" => "nullable",
            "content.difficulity" => "required_if:type," . ContentTypeEnum::QUESTION->value,
            "content.difficulty_level" => "nullable",
            "content.question" => "required_if:type," . ContentTypeEnum::QUESTION->value,
            "content.degree" => "required_if:type," . ContentTypeEnum::QUESTION->value,
            "content.time" => "nullable",

            // question answers
            "content.answers" => "required_if:type," . ContentTypeEnum::QUESTION->value . "|array",
            "content.answers.*.answer" => "required_if:type," . ContentTypeEnum::QUESTION->value,
            "content.answers.*.is_correct" => "required_if:type," . ContentTypeEnum::QUESTION->value . "|boolean",

            // question answer attachments
            "content.answers.*.attachments" => "nullable|array",
            "content.answers.*.attachments.*.media" => "nullable|string",
            "content.answers.*.attachments.*.type" => "nullable|integer",
            "content.answers.*.attachments.*.alt" => "nullable|string",

            // question attachments
            "content.attachments" => "nullable|array",
            "content.attachments.*.media" => "nullable|string",
            "content.attachments.*.type" => "nullable|integer",
            "content.attachments.*.alt" => "nullable|string",

        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->handleContentValidation($validator);
        });
    }
    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {

    //         // Extracting input values
    //         $courseId = $this->get('course_id');
    //         $lessonId = $this->get('lesson_id');
    //         $hasLessonId = $this->has('lesson_id');
    //         $type = $this->get('type');
    //         $isFile = data_get($this->input(), 'content.is_file');
    //         $file = data_get($this->input(), 'content.file');
    //         $link = data_get($this->input(), 'content.link');
    //         $can_skip = data_get($this->input(), 'content.can_skip');
    //         $skip_rate = data_get($this->input(), 'content.skip_rate');
    //         $audio_type = data_get($this->input(), 'content.audio_type');
    //         $document_type = data_get($this->input(), 'content.document_type');
    //         $session_type = data_get($this->input(), 'content.session_type');
    //         $answers = data_get($this->input(), 'content.answers');
    //         $group_id = data_get($this->input(), 'content.group_id');


    //         if (!$courseId) {
    //             return;
    //         }
    //         // Rule: if type is poll, certain fields must not be sent
    //         if ($type == ContentTypeEnum::POLL->value || $type == ContentTypeEnum::QUESTION->value) {
    //             if ($link) {
    //                 $validator->errors()->add('content.link', 'The link must not be sent when type is poll{4}.');
    //             }
    //             if ($isFile === 0 || $isFile == 1) {
    //                 $validator->errors()->add('content.is_file', 'The is_file must not be sent when type is poll{4}.');
    //             }
    //             if ($file) {
    //                 $validator->errors()->add('content.file', 'The file must not be sent when type is poll{4}.');
    //             }
    //             if ($can_skip) {
    //                 $validator->errors()->add('content.can_skip', 'The can_skip must not be sent when type is poll{4}.');
    //             }
    //             if ($skip_rate) {
    //                 $validator->errors()->add('content.skip_rate', 'The skip_rate must not be sent when type is poll{4}.');
    //             }
    //             if ($audio_type) {
    //                 $validator->errors()->add('content.audio_type', 'The audio_type must not be sent when type is poll{4}.');
    //             }
    //             if ($document_type) {
    //                 $validator->errors()->add('content.document_type', 'The document_type must not be sent when type is poll{4}.');
    //             }
    //             if ($session_type) {
    //                 $validator->errors()->add('content.session_type', 'The session_type must not be sent when type is poll{4}.');
    //             }
    //             return;
    //         } else {
    //             // Rule: if type is not poll, answers must not be sent
    //             if ($answers) {
    //                 $validator->errors()->add('content.answers', 'The answers must not be sent when type is not poll{4}.');
    //             }
    //         }
    //         $course = \App\Modules\Course\Infrastructure\Persistence\Models\Course\Course::find($courseId);

    //         // Rule: course_id must exist
    //         if (!$course) {
    //             $validator->errors()->add('course_id', 'Course not found.');
    //             return;
    //         }
    //         if (!in_array($type, [ContentTypeEnum::LIVE->value, ContentTypeEnum::EXAM->value])) {

    //             // Rule: lesson_id must not be sent if level_type == 1
    //             if ($course->level_type == 1 && $hasLessonId && $type === ContentTypeEnum::LIVE->value) {
    //                 $validator->errors()->add('lesson_id', 'The lesson_id must not be sent when the course level type is 1.');
    //             }

    //             // Rule: lesson_id is required if level_type == 2 or 3
    //             if (in_array($course->level_type, [2, 3]) && !$hasLessonId && $type !== ContentTypeEnum::LIVE->value) {
    //                 $validator->errors()->add('lesson_id', 'The lesson_id is required when the course level type is 2 or 3.');
    //             }
    //             // Rule: lesson_id must belong to the same course
    //             if ($hasLessonId) {
    //                 $lesson = \App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson::find($lessonId);

    //                 if (!$lesson) {
    //                     $validator->errors()->add('lesson_id', 'The specified lesson does not exist.');
    //                 } elseif ($lesson->course_id != $courseId) {
    //                     $validator->errors()->add('lesson_id', 'The lesson_id must belong to the specified course.');
    //                 }
    //             }

    //             // 👇 Custom mutual exclusivity between content.file and content.link


    //             if ($type !== ContentTypeEnum::EXAM->value) {
    //                 if ($isFile == 1) {
    //                     if (!$file) {
    //                         $validator->errors()->add('content.file', 'The file is required when is_file is 1.');
    //                     }

    //                     if ($link) {
    //                         $validator->errors()->add('content.link', 'The link must not be sent when is_file is 1.');
    //                     }
    //                 } elseif ($isFile == 0) {
    //                     if (!$link) {
    //                         $validator->errors()->add('content.link', 'The link is required when is_file is 0.');
    //                     }

    //                     if ($file) {
    //                         $validator->errors()->add('content.file', 'The file must not be sent when is_file is 0.');
    //                     }
    //                 }
    //             }
    //         }
    //         if ($type == ContentTypeEnum::LIVE->value) {
    //             $group = Group::find($group_id);
    //             if (!$group_id) {
    //                 $validator->errors()->add('content.group_id', 'The group_id is required when type is live.');
    //             }
    //             if (isset($group) && $group->course_id != $courseId) {
    //                 $validator->errors()->add('content.group_id', 'The group_id must belong to the specified course.');
    //             }
    //         }

    //         if ($type !== ContentTypeEnum::QUESTION->value) {
    //             $forbiddenFields = [
    //                 'question_type',
    //                 'identicality',
    //                 'identicality_percentage',
    //                 'difficulty',
    //                 'difficulty_level',
    //                 'question',
    //                 'degree',
    //                 'time',
    //                 'answers',
    //                 'attachments',
    //             ];

    //             foreach ($forbiddenFields as $field) {
    //                 if ($this->has($field)) {
    //                     $validator->errors()->add($field, "The $field must not be sent when type is not QUESTION.");
    //                 }
    //             }

    //             // Check nested fields for answers
    //             $answers = $this->input('answers');
    //             if (is_array($answers)) {
    //                 foreach ($answers as $index => $answer) {
    //                     if (isset($answer['answer'])) {
    //                         $validator->errors()->add("answers.$index.answer", "The answer must not be sent when type is not QUESTION.");
    //                     }
    //                     if (isset($answer['is_correct'])) {
    //                         $validator->errors()->add("answers.$index.is_correct", "The is_correct must not be sent when type is not QUESTION.");
    //                     }
    //                     if (isset($answer['attachments'])) {
    //                         foreach ($answer['attachments'] as $attIndex => $attachment) {
    //                             if (isset($attachment['media'])) {
    //                                 $validator->errors()->add("answers.$index.attachments.$attIndex.media", "The media must not be sent when type is not QUESTION.");
    //                             }
    //                             if (isset($attachment['type'])) {
    //                                 $validator->errors()->add("answers.$index.attachments.$attIndex.type", "The type must not be sent when type is not QUESTION.");
    //                             }
    //                             if (isset($attachment['alt'])) {
    //                                 $validator->errors()->add("answers.$index.attachments.$attIndex.alt", "The alt must not be sent when type is not QUESTION.");
    //                             }
    //                         }
    //                     }
    //                 }
    //             }

    //             // Check general attachments
    //             if ($this->has('attachments')) {
    //                 $attachments = $this->input('attachments');
    //                 if (is_array($attachments)) {
    //                     foreach ($attachments as $i => $attachment) {
    //                         if (isset($attachment['media'])) {
    //                             $validator->errors()->add("attachments.$i.media", "The media must not be sent when type is not QUESTION.");
    //                         }
    //                         if (isset($attachment['type'])) {
    //                             $validator->errors()->add("attachments.$i.type", "The type must not be sent when type is not QUESTION.");
    //                         }
    //                         if (isset($attachment['alt'])) {
    //                             $validator->errors()->add("attachments.$i.alt", "The alt must not be sent when type is not QUESTION.");
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     });
    // }
}
