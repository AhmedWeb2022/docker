<?php

namespace App\Modules\Course\Application\UseCases\Rate;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Rate\RateDTO;
use App\Modules\Course\Http\Resources\Rate\RateResource;
use App\Modules\Course\Application\DTOS\Rate\RateFilterDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Rate\RateRepository;

class RateUseCase
{

    protected $rateRepository;
    protected $user;


    public function __construct(RateRepository $rateRepository)
    {
        $this->rateRepository = $rateRepository;
        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        // dd($this->user);
    }

    public function fetchRates(RateFilterDTO $rateFilterDTO): DataStatus
    {
        try {
            $courseRates = $this->rateRepository->getWhereHas(
                relation: 'courses',
                key: 'course_id',
                value: $rateFilterDTO->course_id
            )->pluck('id')->toArray();
            $rates = $this->rateRepository->filter(
                dto: $rateFilterDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $rateFilterDTO->paginate,
                limit: $rateFilterDTO->limit
            )->whereIn('id', $courseRates);
            $resource =  RateResource::collection($rates);
            return DataSuccess(
                status: true,
                message: 'Rates fetched successfully',
                data: $rateFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchRateDetails(RateFilterDTO $rateFilterDTO): DataStatus
    {
        try {
            $rate = $this->rateRepository->getById($rateFilterDTO->rate_id);
            $resource = new RateResource($rate);
            return DataSuccess(
                status: true,
                message: 'Rate fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createRate(RateDTO $rateDTO): DataStatus
    {
        try {
            // dd($rateDTO->toArray());
            $rateDTO->user_id = $this->user->id;
            $rate = $this->rateRepository->updateOrCreate($rateDTO);

            return DataSuccess(
                status: true,
                message: 'Rate created successfully',
                data: true //new RateResource($rate)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateRate(RateDTO $rateDTO): DataStatus
    {
        try {
            $rate = $this->rateRepository->update($rateDTO->rate_id, $rateDTO);
            return DataSuccess(
                status: true,
                message: ' Rate updated successfully',
                data: new RateResource($rate)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteRate(RateFilterDTO $rateFilterDTO): DataStatus
    {
        try {
            $rate = $this->rateRepository->delete($rateFilterDTO->rate_id);
            return DataSuccess(
                status: true,
                message: ' Rate deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
