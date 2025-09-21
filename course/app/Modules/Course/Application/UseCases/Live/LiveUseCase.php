<?php

namespace App\Modules\Course\Application\UseCases\Live;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Live\LiveDTO;
use App\Modules\Course\Http\Resources\Live\LiveResource;
use App\Modules\Course\Application\DTOS\Live\LiveFilterDTO;
use App\Modules\Course\Http\Resources\Live\FullLiveResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Live\LiveRepository;

class LiveUseCase
{

    protected $liveRepository;



    public function __construct()
    {
        $this->liveRepository = new LiveRepository();
    }

    public function fetchLives(LiveFilterDTO $liveFilterDTO, $withChild = false): DataStatus
    {
        try {
            $lives = $this->liveRepository->filter($liveFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullLiveResource::collection($lives) : LiveResource::collection($lives)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchLiveDetails(LiveFilterDTO $liveFilterDTO): DataStatus
    {
        try {
            $live = $this->liveRepository->getById($liveFilterDTO->live_id);
            return DataSuccess(
                status: true,
                message: 'Course Live fetched successfully',
                // data: new LiveResource($live)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createLive(LiveDTO $liveDTO): DataStatus
    {
        try {
            $live = $this->liveRepository->create($liveDTO);

            $live->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Live created successfully',
                data: $live
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLive(LiveDTO $liveDTO): DataStatus
    {
        try {
            $live = $this->liveRepository->update($liveDTO->live_id, $liveDTO);
            return DataSuccess(
                status: true,
                message: 'Course Live updated successfully',
                data: $live
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteLive(LiveFilterDTO $liveFilterDTO): DataStatus
    {
        try {
            $live = $this->liveRepository->delete($liveFilterDTO->live_id);
            return DataSuccess(
                status: true,
                message: 'Course Live deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
