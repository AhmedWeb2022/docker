<?php

namespace App\Modules\Course\Application\Trait\Content;

use Illuminate\Validation\Validator;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Group\Group;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;

trait HandleContentRequestTrait
{
    public function handleContentValidation(Validator $validator): void
    {
        $input = $this->input();
        $type = $this->get('type');
        $courseId = $this->get('course_id');
        $lessonId = $this->get('lesson_id');
        $hasLessonId = $this->has('lesson_id');

        $isFile = data_get($input, 'content.is_file');
        $file = data_get($input, 'content.file');
        $link = data_get($input, 'content.link');
        $can_skip = data_get($input, 'content.can_skip');
        $skip_rate = data_get($input, 'content.skip_rate');
        $main_can_skip = data_get($input, 'can_skip');
        $main_skip_rate = data_get($input, 'skip_rate');
        $audio_type = data_get($input, 'content.audio_type');
        $document_type = data_get($input, 'content.document_type');
        $session_type = data_get($input, 'content.session_type');
        $answers = data_get($input, 'content.answers');
        $group_id = data_get($input, 'content.group_id');

        if (!$courseId) return;

        if (in_array($type, [ContentTypeEnum::POLL->value, ContentTypeEnum::QUESTION->value])) {
            $this->validatePollOrQuestionType($validator, $isFile, $file, $link, $can_skip, $skip_rate, $audio_type, $document_type, $session_type);
        } else {
            if ($answers) {
                $validator->errors()->add('content.answers', 'The answers must not be sent when type is not poll.');
            }
        }

        $course = Course::find($courseId);
        if (!$course) {
            $validator->errors()->add('course_id', 'Course not found.');
            return;
        }

        $this->validateLessonRules($validator, $course, $type, $hasLessonId, $lessonId, $courseId);
        $this->validateFileOrLink($validator, $type, $isFile, $file, $link);
        $this->validateLiveContent($validator, $type, $group_id, $courseId);
        $this->validateForbiddenQuestionFields($validator, $type);
        $this->validateSkipRate($validator, $type, $main_can_skip, $main_skip_rate);
    }
    protected function validateSkipRate($validator, $type, $can_skip, $skip_rate): void
    {
        $allowedTypes = [
            ContentTypeEnum::SESSION->value,
            ContentTypeEnum::AUDIO->value,
        ];

        if (in_array($type, $allowedTypes)) {
            // Required if can_skip == 1
            if ($can_skip == 1 && (is_null($skip_rate) || $skip_rate === '')) {
                $validator->errors()->add('skip_rate', 'The skip_rate field is required when can_skip is 1 and type is session or audio.');
            }

            // Must be integer if sent
            if (!is_null($skip_rate) && !is_numeric($skip_rate)) {
                $validator->errors()->add('skip_rate', 'The skip_rate must be an integer.');
            }
        } else {
            // For other types, skip_rate must not be sent
            if (!is_null($skip_rate)) {
                $validator->errors()->add('skip_rate', 'The skip_rate must not be sent when type is not session or audio.');
            }
        }
    }

    protected function validatePollOrQuestionType($validator, $isFile, $file, $link, $can_skip, $skip_rate, $audio_type, $document_type, $session_type)
    {
        $fields = compact('isFile', 'file', 'link', 'can_skip', 'skip_rate', 'audio_type', 'document_type', 'session_type');

        foreach ($fields as $key => $value) {
            if (!empty($value)) {
                $validator->errors()->add("content.$key", "The $key must not be sent when type is poll or question.");
            }
        }
    }

    protected function validateLessonRules($validator, $course, $type, $hasLessonId, $lessonId, $courseId)
    {
        if (!in_array($type, [ContentTypeEnum::LIVE->value, ContentTypeEnum::EXAM->value])) {
            if ($course->level_type == 1 && $hasLessonId && $type === ContentTypeEnum::LIVE->value) {
                $validator->errors()->add('lesson_id', 'The lesson_id must not be sent when the course level type is 1.');
            }

            if (in_array($course->level_type, [2, 3]) && !$hasLessonId && $type !== ContentTypeEnum::LIVE->value) {
                $validator->errors()->add('lesson_id', 'The lesson_id is required when the course level type is 2 or 3.');
            }

            if ($hasLessonId) {
                $lesson = Lesson::find($lessonId);
                if (!$lesson || $lesson->course_id != $courseId) {
                    $validator->errors()->add('lesson_id', 'The lesson_id must belong to the specified course.');
                }
            }
        }
    }

    protected function validateFileOrLink($validator, $type, $isFile, $file, $link)
    {
        if ($type !== ContentTypeEnum::EXAM->value && $type !== ContentTypeEnum::LIVE->value && $type !== ContentTypeEnum::QUESTION->value) {
            if ($isFile == 1) {
                if (!$file) {
                    $validator->errors()->add('content.file', 'The file is required when is_file is 1.');
                }
                if ($link) {
                    $validator->errors()->add('content.link', 'The link must not be sent when is_file is 1.');
                }
            } elseif ($isFile == 0) {
                if (!$link) {
                    $validator->errors()->add('content.link', 'The link is required when is_file is 0.');
                }
                if ($file) {
                    $validator->errors()->add('content.file', 'The file must not be sent when is_file is 0.');
                }
            }
        }
    }

    protected function validateLiveContent($validator, $type, $group_id, $courseId)
    {
        if ($type == ContentTypeEnum::LIVE->value) {
            if (!$group_id) {
                $validator->errors()->add('content.group_id', 'The group_id is required when type is live.');
                return;
            }

            $group = Group::find($group_id);
            if ($group && $group->course_id != $courseId) {
                $validator->errors()->add('content.group_id', 'The group_id must belong to the specified course.');
            }
        }
    }

    protected function validateForbiddenQuestionFields($validator, $type)
    {
        if ($type !== ContentTypeEnum::QUESTION->value) {
            $fields = [
                'question_type',
                'identicality',
                'identicality_percentage',
                'difficulty',
                'difficulty_level',
                'question',
                'degree',
                'time',
                'answers',
                'attachments',
            ];

            foreach ($fields as $field) {
                if ($this->has($field)) {
                    $validator->errors()->add($field, "The $field must not be sent when type is not QUESTION.");
                }
            }

            $answers = $this->input('answers');
            if (is_array($answers)) {
                foreach ($answers as $index => $answer) {
                    foreach (['answer', 'is_correct'] as $key) {
                        if (isset($answer[$key])) {
                            $validator->errors()->add("answers.$index.$key", "The $key must not be sent when type is not QUESTION.");
                        }
                    }

                    if (isset($answer['attachments'])) {
                        foreach ($answer['attachments'] as $attIndex => $attachment) {
                            foreach (['media', 'type', 'alt'] as $attr) {
                                if (isset($attachment[$attr])) {
                                    $validator->errors()->add("answers.$index.attachments.$attIndex.$attr", "The $attr must not be sent when type is not QUESTION.");
                                }
                            }
                        }
                    }
                }
            }

            $attachments = $this->input('attachments');
            if (is_array($attachments)) {
                foreach ($attachments as $i => $attachment) {
                    foreach (['media', 'type', 'alt'] as $attr) {
                        if (isset($attachment[$attr])) {
                            $validator->errors()->add("attachments.$i.$attr", "The $attr must not be sent when type is not QUESTION.");
                        }
                    }
                }
            }
        }
    }
}
