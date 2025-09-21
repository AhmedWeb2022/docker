<?php

namespace App\Modules\Course\Application\UseCases\Poll;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Poll\PollDTO;
use App\Modules\Course\Http\Resources\Poll\PollResource;
use App\Modules\Course\Application\DTOS\Poll\PollFilterDTO;
use App\Modules\Course\Http\Resources\Poll\FullPollResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Poll\PollRepository;

class PollUseCase
{

    protected $pollRepository;



    public function __construct()
    {
        $this->pollRepository = new PollRepository();
    }

    public function fetchPolls(PollFilterDTO $pollFilterDTO, $withChild = false): DataStatus
    {
        try {
            $polls = $this->pollRepository->filter($pollFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullPollResource::collection($polls) : PollResource::collection($polls)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchPollDetails(PollFilterDTO $pollFilterDTO): DataStatus
    {
        try {
            $poll = $this->pollRepository->getById($pollFilterDTO->poll_id);
            return DataSuccess(
                status: true,
                message: 'Course Poll fetched successfully',
                // data: new PollResource($poll)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createPoll(PollDTO $pollDTO): DataStatus
    {
        try {
            $poll = $this->pollRepository->create($pollDTO);

            $poll->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Poll created successfully',
                data: $poll
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updatePoll(PollDTO $pollDTO): DataStatus
    {
        try {
            $poll = $this->pollRepository->update($pollDTO->poll_id, $pollDTO);
            return DataSuccess(
                status: true,
                message: 'Course Poll updated successfully',
                data: $poll
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deletePoll(PollFilterDTO $pollFilterDTO): DataStatus
    {
        try {
            $poll = $this->pollRepository->delete($pollFilterDTO->poll_id);
            return DataSuccess(
                status: true,
                message: 'Course Poll deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
