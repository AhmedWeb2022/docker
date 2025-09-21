<?php

namespace App\Modules\Course\Application\UseCases\LiveQuestion;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\LiveQuestion\LiveQuestionDTO;
use App\Modules\Course\Http\Resources\LiveQuestion\LiveQuestionResource;
use App\Modules\Course\Application\DTOS\LiveQuestion\LiveQuestionFilterDTO;
use App\Modules\Course\Http\Resources\LiveQuestion\FullLiveQuestionResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveQuestion\LiveQuestionRepository;

class LiveQuestionUseCase
{

    protected $liveQuestionRepository;



    public function __construct()
    {
        $this->liveQuestionRepository = new LiveQuestionRepository();
    }

    public function fetchLiveQuestions(LiveQuestionFilterDTO $liveQuestionFilterDTO, $withChild = false): DataStatus
    {
        try {
            $liveQuestions = $this->liveQuestionRepository->filter($liveQuestionFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullLiveQuestionResource::collection($liveQuestions) : LiveQuestionResource::collection($liveQuestions)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchLiveQuestionDetails(LiveQuestionFilterDTO $liveQuestionFilterDTO): DataStatus
    {
        try {
            $liveQuestion = $this->liveQuestionRepository->getById($liveQuestionFilterDTO->live_question_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestion fetched successfully',
                // data: new LiveQuestionResource($liveQuestion)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createLiveQuestion(LiveQuestionDTO $liveQuestionDTO): DataStatus
    {
        try {

            $liveQuestion = $this->liveQuestionRepository->create($liveQuestionDTO);

            $liveQuestion->refresh();
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestion created successfully',
                data: $liveQuestion
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLiveQuestion(LiveQuestionDTO $liveQuestionDTO): DataStatus
    {
        try {
            $liveQuestion = $this->liveQuestionRepository->update($liveQuestionDTO->live_question_id, $liveQuestionDTO);
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestion updated successfully',
                data: $liveQuestion
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteLiveQuestion(LiveQuestionFilterDTO $liveQuestionFilterDTO): DataStatus
    {
        try {
            $liveQuestion = $this->liveQuestionRepository->delete($liveQuestionFilterDTO->live_question_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveQuestion deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
