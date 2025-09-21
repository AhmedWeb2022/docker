<?php

namespace App\Modules\Course\Application\UseCases\Partner;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Application\DTOS\Partner\PartnerDTO;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Http\Resources\Partner\PartnerResource;
use App\Modules\Course\Application\DTOS\Partner\PartnerFilterDTO;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Partner\FullPartnerResource;
use App\Modules\Course\Http\Resources\Partner\Api\PartnerCourseResource;
use App\Modules\Course\Http\Resources\Partner\PartnerWithContentResource;
use App\Modules\Course\Infrastructure\Persistence\Models\Partner\Partner;
use App\Modules\Course\Http\Resources\Partner\PartnerWithChildrenResource;
use App\Modules\Course\Http\Resources\Partner\Api\PartnerDetailsFullResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Partner\PartnerRepository;

class PartnerUseCase
{

    protected $partnerRepository;
    protected $employee;


    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchPartners(PartnerFilterDTO $partnerFilterDTO): DataStatus
    {
        try {
            $partners = $this->partnerRepository->filter(
                dto: $partnerFilterDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $partnerFilterDTO->paginate,
                limit: $partnerFilterDTO->limit
            );
            $resource =  PartnerResource::collection($partners);
            return DataSuccess(
                status: true,
                message: 'Partners fetched successfully',
                data: $partnerFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchPartnerDetails(PartnerFilterDTO $partnerFilterDTO, $viewType = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $partner = $this->partnerRepository->getById($partnerFilterDTO->partner_id);
            // dd($partner->certificates);
            $resource = $viewType == ViewTypeEnum::WEBSITE->value ? new PartnerDetailsFullResource($partner) : new PartnerResource($partner);
            return DataSuccess(
                status: true,
                message: 'Partner fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createPartner(PartnerDTO $partnerDTO): DataStatus
    {
        try {
            $partner = $this->partnerRepository->create($partnerDTO);

            return DataSuccess(
                status: true,
                message: 'Partner created successfully',
                data: new PartnerResource($partner)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updatePartner(PartnerDTO $partnerDTO): DataStatus
    {
        try {
            $partner = $this->partnerRepository->update($partnerDTO->partner_id, $partnerDTO);
            return DataSuccess(
                status: true,
                message: ' Partner updated successfully',
                data: new PartnerResource($partner)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deletePartner(PartnerFilterDTO $partnerFilterDTO): DataStatus
    {
        try {
            $partner = $this->partnerRepository->delete($partnerFilterDTO->partner_id);
            return DataSuccess(
                status: true,
                message: ' Partner deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchPartnerCourses(PartnerFilterDTO $partnerFilterDTO, $viewType = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $partner = $this->partnerRepository->getById($partnerFilterDTO->partner_id);
            if (!$partner) {
                return DataFailed(
                    status: false,
                    message: 'Partner not found'
                );
            }

            return DataSuccess(
                status: true,
                message: 'Partner courses fetched successfully',
                data: CourseResource::collection($partner->courses)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function handelPartnerViewResource($partners, $view)
    {
        if ($view == ViewTypeEnum::DASHBOARD->value) {
            return  PartnerResource::collection($partners);
        } elseif ($view == ViewTypeEnum::WEBSITE->value) {
            return  PartnerResource::collection($partners);
        } else {
            return  PartnerResource::collection($partners);
        }
    }
}
