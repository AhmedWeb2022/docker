<?php

namespace App\Modules\Course\Application\UseCases\LiveAnswer;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\LiveAnswer\LiveAnswerDTO;
use App\Modules\Course\Http\Resources\LiveAnswer\LiveAnswerResource;
use App\Modules\Course\Application\DTOS\LiveAnswer\LiveAnswerFilterDTO;
use App\Modules\Course\Http\Resources\LiveAnswer\FullLiveAnswerResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveAnswer\LiveAnswerRepository;

class LiveAnswerUseCase
{

    protected $liveAnswerRepository;



    public function __construct()
    {
        $this->liveAnswerRepository = new LiveAnswerRepository();
    }

    public function fetchLiveAnswers(LiveAnswerFilterDTO $liveAnswerFilterDTO, $withChild = false): DataStatus
    {
        try {
            $liveAnswers = $this->liveAnswerRepository->filter($liveAnswerFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullLiveAnswerResource::collection($liveAnswers) : LiveAnswerResource::collection($liveAnswers)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchLiveAnswerDetails(LiveAnswerFilterDTO $liveAnswerFilterDTO): DataStatus
    {
        try {
            $liveAnswer = $this->liveAnswerRepository->getById($liveAnswerFilterDTO->live_answer_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswer fetched successfully',
                data: $liveAnswer
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createLiveAnswer(LiveAnswerDTO $liveAnswerDTO): DataStatus
    {
        try {
            $liveAnswer = $this->liveAnswerRepository->create($liveAnswerDTO);

            $liveAnswer->refresh();
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswer created successfully',
                data: $liveAnswer
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLiveAnswer(LiveAnswerDTO $liveAnswerDTO): DataStatus
    {
        try {
            $liveAnswer = $this->liveAnswerRepository->update($liveAnswerDTO->live_answer_id, $liveAnswerDTO);
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswer updated successfully',
                data: $liveAnswer
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteLiveAnswer(LiveAnswerFilterDTO $liveAnswerFilterDTO): DataStatus
    {
        try {
            $liveAnswer = $this->liveAnswerRepository->delete($liveAnswerFilterDTO->live_answer_id);
            return DataSuccess(
                status: true,
                message: 'Course LiveAnswer deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
