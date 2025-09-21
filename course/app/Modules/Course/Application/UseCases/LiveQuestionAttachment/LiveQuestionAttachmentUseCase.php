<?php

namespace App\Modules\Course\Application\UseCases\LiveQuestionAttachment;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\LiveQuestionAttachment\LiveQuestionAttachmentDTO;
use App\Modules\Course\Http\Resources\LiveQuestionAttachment\LiveQuestionAttachmentResource;
use App\Modules\Course\Application\DTOS\LiveQuestionAttachment\LiveQuestionAttachmentFilterDTO;
use App\Modules\Course\Http\Resources\LiveQuestionAttachment\FullLiveQuestionAttachmentResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveQuestionAttachment\LiveQuestionAttachmentRepository;

class LiveQuestionAttachmentUseCase
{

    protected $liveQuestionAttachmentRepository;



    public function __construct()
    {
        $this->liveQuestionAttachmentRepository = new LiveQuestionAttachmentRepository();
    }

    public function fetchLiveQuestionAttachments(LiveQuestionAttachmentFilterDTO $liveQuestionAttachmentFilterDTO, $withChild = false): DataStatus
    {
        try {
            $liveQuestionAttachments = $this->liveQuestionAttachmentRepository->filter($liveQuestionAttachmentFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullLiveQuestionAttachmentResource::collection($liveQuestionAttachments) : LiveQuestionAttachmentResource::collection($liveQuestionAttachments)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchLiveQuestionAttachmentDetails(LiveQuestionAttachmentFilterDTO $liveQuestionAttachmentFilterDTO): DataStatus
    {
        try {
            $liveQuestionAttachment = $this->liveQuestionAttachmentRepository->getById($liveQuestionAttachmentFilterDTO->live_question_attachment_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestionAttachment fetched successfully',
                // data: new LiveQuestionAttachmentResource($liveQuestionAttachment)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createLiveQuestionAttachment(LiveQuestionAttachmentDTO $liveQuestionAttachmentDTO): DataStatus
    {
        try {
            $liveQuestionAttachment = $this->liveQuestionAttachmentRepository->create($liveQuestionAttachmentDTO);

            $liveQuestionAttachment->refresh();
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestionAttachment created successfully',
                data: $liveQuestionAttachment
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLiveQuestionAttachment(LiveQuestionAttachmentDTO $liveQuestionAttachmentDTO): DataStatus
    {
        try {
            $liveQuestionAttachment = $this->liveQuestionAttachmentRepository->update($liveQuestionAttachmentDTO->live_question_attachment_id, $liveQuestionAttachmentDTO);
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestionAttachment updated successfully',
                data: $liveQuestionAttachment
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteLiveQuestionAttachment(LiveQuestionAttachmentFilterDTO $liveQuestionAttachmentFilterDTO): DataStatus
    {
        try {
            $liveQuestionAttachment = $this->liveQuestionAttachmentRepository->delete($liveQuestionAttachmentFilterDTO->live_question_attachment_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestionAttachment deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
