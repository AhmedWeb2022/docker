<?php

namespace App\Modules\Course\Application\UseCases\Referance;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Referance\ReferanceDTO;
use App\Modules\Course\Http\Resources\Referance\ReferanceResource;
use App\Modules\Course\Application\DTOS\Referance\ReferanceFilterDTO;
use App\Modules\Course\Http\Resources\Referance\FullReferanceResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Referance\ReferanceRepository;

class ReferanceUseCase
{

    protected $referanceRepository;



    public function __construct()
    {
        $this->referanceRepository = new ReferanceRepository();
    }

    public function fetchReferances(ReferanceFilterDTO $referanceFilterDTO, $withChild = false): DataStatus
    {
        try {
            $referances = $this->referanceRepository->filter($referanceFilterDTO);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                // data: $withChild ? FullReferanceResource::collection($referances) : ReferanceResource::collection($referances)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchReferanceDetails(ReferanceFilterDTO $referanceFilterDTO): DataStatus
    {
        try {
            $referance = $this->referanceRepository->getById($referanceFilterDTO->referance_id);
            return DataSuccess(
                status: true,
                message: 'Course Referance fetched successfully',
                // data: new ReferanceResource($referance)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createReferance(ReferanceDTO $referanceDTO): DataStatus
    {
        try {
            $referance = $this->referanceRepository->create($referanceDTO);

            $referance->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Referance created successfully',
                data: $referance
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateReferance(ReferanceDTO $referanceDTO): DataStatus
    {
        try {
            $referance = $this->referanceRepository->update($referanceDTO->referance_id, $referanceDTO);
            return DataSuccess(
                status: true,
                message: 'Course Referance updated successfully',
                data: $referance
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteReferance(ReferanceFilterDTO $referanceFilterDTO): DataStatus
    {
        try {
            $referance = $this->referanceRepository->delete($referanceFilterDTO->referance_id);
            return DataSuccess(
                status: true,
                message: 'Course Referance deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
