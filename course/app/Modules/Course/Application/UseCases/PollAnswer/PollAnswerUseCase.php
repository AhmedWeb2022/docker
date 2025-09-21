<?php

namespace App\Modules\Course\Application\UseCases\PollAnswer;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\PollAnswer\PollAnswerDTO;
use App\Modules\Course\Http\Resources\PollAnswer\PollAnswerResource;
use App\Modules\Course\Application\DTOS\PollAnswer\PollAnswerFilterDTO;
use App\Modules\Course\Http\Resources\PollAnswer\FullPollAnswerResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\PollAnswer\PollAnswerRepository;

class PollAnswerUseCase
{

    protected $pollAnswerRepository;



    public function __construct()
    {
        $this->pollAnswerRepository = new PollAnswerRepository();
    }

    public function fetchPollAnswers(PollAnswerFilterDTO $pollAnswerFilterDTO, $withChild = false): DataStatus
    {
        try {
            $pollAnswers = $this->pollAnswerRepository->filter($pollAnswerFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullPollAnswerResource::collection($pollAnswers) : PollAnswerResource::collection($pollAnswers)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchPollAnswerDetails(PollAnswerFilterDTO $pollAnswerFilterDTO): DataStatus
    {
        try {
            $pollAnswer = $this->pollAnswerRepository->getById($pollAnswerFilterDTO->poll_answer_id);
            return DataSuccess(
                status: true,
                message: 'Course PollAnswer fetched successfully',
                // data: new PollAnswerResource($pollAnswer)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createPollAnswer(PollAnswerDTO $pollAnswerDTO): DataStatus
    {
        try {
            $pollAnswer = $this->pollAnswerRepository->create($pollAnswerDTO);

            $pollAnswer->refresh();
            return DataSuccess(
                status: true,
                message: 'Course PollAnswer created successfully',
                data: $pollAnswer
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updatePollAnswer(PollAnswerDTO $pollAnswerDTO): DataStatus
    {
        try {
            $pollAnswer = $this->pollAnswerRepository->update($pollAnswerDTO->poll_answer_id, $pollAnswerDTO);
            return DataSuccess(
                status: true,
                message: 'Course PollAnswer updated successfully',
                data: $pollAnswer
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deletePollAnswer(PollAnswerFilterDTO $pollAnswerFilterDTO): DataStatus
    {
        try {
            $pollAnswer = $this->pollAnswerRepository->delete($pollAnswerFilterDTO->poll_answer_id);
            return DataSuccess(
                status: true,
                message: 'Course PollAnswer deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
