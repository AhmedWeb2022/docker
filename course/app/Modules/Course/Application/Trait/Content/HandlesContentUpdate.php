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

// Import all DTOs and enums like in HandlesContentCreation

trait HandlesContentUpdate
{
    protected function handleContentUpdate($contentDTO, $content): void
    {
        match ($content->type) {
            ContentTypeEnum::SESSION->value => $this->updateSession($contentDTO, $content),
            ContentTypeEnum::AUDIO->value => $this->updateAudio($contentDTO, $content),
            ContentTypeEnum::DOCUMENT->value => $this->updateDocument($contentDTO, $content),
            ContentTypeEnum::POLL->value => $this->updatePoll($contentDTO, $content),
            ContentTypeEnum::LIVE->value => $this->updateLive($contentDTO, $content),
            ContentTypeEnum::QUESTION->value => $this->updateQuestion($contentDTO, $content),
            default => null,
        };

        // if (!empty($contentDTO->referances)) {
        //     $this->updateReferances($content, $contentDTO->referances);
        // }
    }

    protected function updateSession($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var SessionDTO $dto */
        $dto = SessionDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $dto->session_id = $content->session->id;
        $this->sessionUseCase->updateSession($dto);
        DB::commit();
    }

    protected function updateAudio($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var AudioDTO $dto */
        $dto = AudioDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $dto->audio_id = $content->audio->id;
        $this->audioUseCase->updateAudio($dto);
        DB::commit();
    }

    protected function updateDocument($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var DocumentDTO $dto */
        $dto = DocumentDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $dto->document_id = $content->document->id;
        $this->documentUseCase->updateDocument($dto);
        DB::commit();
    }

    protected function updatePoll($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var PollDTO $pollDTO */
        $pollDTO = PollDTO::fromArray($contentDTO->content);
        $pollDTO->content_id = $content->id;
        $pollDTO->poll_id = $content->poll->id;

        $this->pollUseCase->updatePoll($pollDTO);
        DB::commit();
    }

    protected function updateLive($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var LiveDTO $dto */
        $dto = LiveDTO::fromArray($contentDTO->content);
        $dto->content_id = $content->id;
        $dto->course_id = $content->course_id;
        $dto->live_id = $content->live->id;
        $this->liveUseCase->updateLive($dto);
        DB::commit();
    }

    protected function updateQuestion($contentDTO, $content): void
    {
        DB::beginTransaction();
        /** @var LiveQuestionDTO $questionDTO */
        $questionDTO = LiveQuestionDTO::fromArray($contentDTO->content);
        $questionDTO->content_id = $content->id;
        $questionDTO->creator = $this->employee->id;
        $questionDTO->live_question_id = $content->liveQuestion->id;
        $this->liveQuestionUseCase->updateLiveQuestion($questionDTO);
        DB::commit();
    }
}
