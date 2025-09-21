<?php

namespace App\Modules\Course\Application\UseCases\LiveAnswerAttachment;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\LiveAnswerAttachment\LiveAnswerAttachmentDTO;
use App\Modules\Course\Http\Resources\LiveAnswerAttachment\LiveAnswerAttachmentResource;
use App\Modules\Course\Application\DTOS\LiveAnswerAttachment\LiveAnswerAttachmentFilterDTO;
use App\Modules\Course\Http\Resources\LiveAnswerAttachment\FullLiveAnswerAttachmentResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveAnswerAttachment\LiveAnswerAttachmentRepository;

class LiveAnswerAttachmentUseCase
{

    protected $liveAnswerAttachmentRepository;



    public function __construct()
    {
        $this->liveAnswerAttachmentRepository = new LiveAnswerAttachmentRepository();
    }

    public function fetchLiveAnswerAttachments(LiveAnswerAttachmentFilterDTO $liveAnswerAttachmentFilterDTO, $withChild = false): DataStatus
    {
        try {
            $liveAnswerAttachments = $this->liveAnswerAttachmentRepository->filter($liveAnswerAttachmentFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullLiveAnswerAttachmentResource::collection($liveAnswerAttachments) : LiveAnswerAttachmentResource::collection($liveAnswerAttachments)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchLiveAnswerAttachmentDetails(LiveAnswerAttachmentFilterDTO $liveAnswerAttachmentFilterDTO): DataStatus
    {
        try {
            $liveAnswerAttachment = $this->liveAnswerAttachmentRepository->getById($liveAnswerAttachmentFilterDTO->live_answer_attachment_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswerAttachment fetched successfully',
                // data: new LiveAnswerAttachmentResource($liveAnswerAttachment)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createLiveAnswerAttachment(LiveAnswerAttachmentDTO $liveAnswerAttachmentDTO): DataStatus
    {
        try {
            $liveAnswerAttachment = $this->liveAnswerAttachmentRepository->create($liveAnswerAttachmentDTO);

            $liveAnswerAttachment->refresh();
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswerAttachment created successfully',
                data: $liveAnswerAttachment
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLiveAnswerAttachment(LiveAnswerAttachmentDTO $liveAnswerAttachmentDTO): DataStatus
    {
        try {
            $liveAnswerAttachment = $this->liveAnswerAttachmentRepository->update($liveAnswerAttachmentDTO->live_answer_attachment_id, $liveAnswerAttachmentDTO);
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswerAttachment updated successfully',
                data: $liveAnswerAttachment
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteLiveAnswerAttachment(LiveAnswerAttachmentFilterDTO $liveAnswerAttachmentFilterDTO): DataStatus
    {
        try {
            $liveAnswerAttachment = $this->liveAnswerAttachmentRepository->delete($liveAnswerAttachmentFilterDTO->live_answer_attachment_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswerAttachment deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
