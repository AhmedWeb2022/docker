<?php

namespace App\Modules\Course\Application\Trait\Content;


use Illuminate\Support\Facades\DB;

// Content DTOs
use App\Modules\Course\Application\DTOS\Live\LiveDTO;
use App\Modules\Course\Application\DTOS\Poll\PollDTO;
use App\Modules\Course\Application\DTOS\Audio\AudioDTO;
use App\Modules\Course\Application\DTOS\Session\SessionDTO;

// Live Question/Answer DTOs
use App\Modules\Course\Application\DTOS\Document\DocumentDTO;
use App\Modules\Course\Application\DTOS\Referance\ReferanceDTO;
use App\Modules\Course\Application\DTOS\LiveAnswer\LiveAnswerDTO;
use App\Modules\Course\Application\DTOS\PollAnswer\PollAnswerDTO;

// Poll Answers and References
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Application\DTOS\LiveQuestion\LiveQuestionDTO;
use App\Modules\Course\Application\DTOS\LiveAnswerAttachment\LiveAnswerAttachmentDTO;
use App\Modules\Course\Application\DTOS\LiveQuestionAttachment\LiveQuestionAttachmentDTO;


trait HandlesContentCreation
{
    protected function handleContentCreation($contentDTO, $content): void
    {
        match ($contentDTO->type) {
            ContentTypeEnum::SESSION->value => $this->handleSession($contentDTO, $content),
            ContentTypeEnum::AUDIO->value => $this->handleAudio($contentDTO, $content),
            ContentTypeEnum::DOCUMENT->value => $this->handleDocument($contentDTO, $content),
            ContentTypeEnum::POLL->value => $this->handlePoll($contentDTO, $content),
            ContentTypeEnum::LIVE->value => $this->handleLive($contentDTO, $content),
            ContentTypeEnum::QUESTION->value => $this->handleQuestion($contentDTO, $content),
            default => null,
        };

        $this->handleReferances($contentDTO, $content);
    }

    protected function handleSession($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var SessionDTO $dto */
        $dto = SessionDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $this->sessionUseCase->createSession($dto);
        DB::commit();
    }

    protected function handleAudio($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var AudioDTO $dto */
        $dto = AudioDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $this->audioUseCase->createAudio($dto);
        DB::commit();
    }

    protected function handleDocument($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var DocumentDTO $dto */
        $dto = DocumentDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $this->documentUseCase->createDocument($dto);
        DB::commit();
    }

    protected function handlePoll($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var PollDTO $pollDTO */
        $pollDTO = PollDTO::fromArray($contentDTO->content);
        $pollDTO->content_id = $content->id;
        $poll = $this->pollUseCase->createPoll($pollDTO)->getData();

        foreach ($contentDTO->content['answers'] ?? [] as $answer) {
            $answer['poll_id'] = $poll->id;
            $this->pollAnswerUseCase->createPollAnswer(PollAnswerDTO::fromArray($answer));
        }
        DB::commit();
    }

    protected function handleLive($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var LiveDTO $dto */
        $dto = LiveDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $dto->course_id = $content->course_id;
        $this->liveUseCase->createLive($dto);
        DB::commit();
    }

    protected function handleQuestion($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var LiveQuestionDTO $questionDTO */
        $questionDTO = LiveQuestionDTO::fromArray($contentDTO->content);
        $questionDTO->content_id = $content->id;
        $questionDTO->creator = $this->employee->id;

        $question = $this->liveQuestionUseCase->createLiveQuestion($questionDTO)->getData();
        // dd($question);
        foreach ($contentDTO->content['answers'] ?? [] as $answer) {
            /** @var LiveAnswerDTO $answerDTO */
            $answerDTO = LiveAnswerDTO::fromArray($answer);
            $answerDTO->live_question_id = $question->id;
            $answerDTO->content_id = $content->id;

            $liveAnswer = $this->liveAnswerUseCase->createLiveAnswer($answerDTO)->getData();

            foreach ($answer['attachments'] ?? [] as $attachment) {
                /** @var LiveAnswerAttachmentDTO $attachmentDTO */
                $attachmentDTO = LiveAnswerAttachmentDTO::fromArray($attachment);
                $attachmentDTO->live_answer_id = $liveAnswer->id;
                $this->liveAnswerAttachmentUseCase->createLiveAnswerAttachment($attachmentDTO);
            }
        }

        foreach ($contentDTO->content['attachments'] ?? [] as $attachment) {
            /** @var LiveQuestionAttachmentDTO $attachmentDTO */
            $attachmentDTO = LiveQuestionAttachmentDTO::fromArray($attachment);
            $attachmentDTO->live_question_id = $question->id;
            $this->liveQuestionAttachmentUseCase->createLiveQuestionAttachment($attachmentDTO);
        }

        DB::commit();
    }

    protected function handleReferances($contentDTO, $content): void
    {
        DB::beginTransaction();
        foreach ($contentDTO->referances ?? [] as $referance) {
            /** @var ReferanceDTO $dto */
            $dto = ReferanceDTO::fromArray($referance);
            $dto->referancable_id = $content->id;
            $dto->referancable_type = get_class($content);
            $this->referanceUseCase->createReferance($dto);
        }
        DB::commit();
    }
}
