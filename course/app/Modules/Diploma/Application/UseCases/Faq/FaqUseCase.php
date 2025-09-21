<?php

namespace App\Modules\Diploma\Application\UseCases\Faq;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Base\Application\Response\DataSuccess;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Diploma\Application\DTOS\Faq\FaqDTO;
use App\Modules\Diploma\Application\DTOS\Faq\FaqFilterDTO;
use App\Modules\Diploma\Http\Resources\Faq\Api\MobileFaqResource;
use App\Modules\Diploma\Http\Resources\Faq\Dashboard\FaqDetailResource;
use App\Modules\Diploma\Http\Resources\Faq\Dashboard\FaqResource;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Faq\FaqRepository;

class FaqUseCase
{
    protected $faqRepository;
    protected $employee;

    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;

        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchFaqs(FaqFilterDTO $faqFilterDto, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $categories = $this->faqRepository->filter(
                $faqFilterDto,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $faqFilterDto->paginate,
                limit: $faqFilterDto->limit
            );
            $resource = $this->HandelFaqResource(
                $categories,
                $view,
                $faqFilterDto->paginate
            );
            return DataSuccess(
                status: true,
                message: 'faq fetched successfully',
                data: $faqFilterDto->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchFaqDetails(FaqFilterDTO $faqFilterDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            $faq = $this->faqRepository->getById($faqFilterDTO->faq_id);
            $resource = $this->HandelFaqDetailResource(
                $faq,
                $view,
                false
            );
            return DataSuccess(
                status: true,
                message: 'Faq fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createFaq(FaqDTO $faqDTO): DataStatus
    {
        try {
            $faqDTO->created_by = $this->employee->id;
            $faq = $this->faqRepository->create($faqDTO);

            $faq->refresh();
            return DataSuccess(
                status: true,
                message: 'faq created successfully',
                data: new FaqResource($faq)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateFaq(FaqDTO $faqDTO): DataStatus
    {
        try {
            $faqDTO->updated_by = $this->employee->id;
            $faq = $this->faqRepository->update($faqDTO->faq_id, $faqDTO);
            $faqDTO->faq_id = $faq->id;
            $faq->refresh();
            return DataSuccess(
                status: true,
                message: 'Faq updated successfully',
                data: new FaqResource($faq)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteFaq(FaqFilterDTO $faqFilterDto): DataStatus
    {
        try {
            $this->faqRepository->delete($faqFilterDto->faq_id);

            return DataSuccess(
                status: true,
                message: 'Faq deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }




    private function HandelFaqResource($faq, $viewType, $paginate)
    {
        if ($viewType == ViewTypeEnum::DASHBOARD->value) {
            return FaqResource::collection($faq);
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            return MobileFaqResource::collection($faq);
        } else {
            return FaqResource::collection($faq);
        }
    }

    public function HandelFaqDetailResource($faq, $viewType, $paginate)
    {
        if ($viewType == ViewTypeEnum::DASHBOARD->value) {
            return FaqDetailResource::make($faq);
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            return MobileFaqResource::collection($faq);
        } else {
            return FaqDetailResource::collection($faq);
        }
    }
}
