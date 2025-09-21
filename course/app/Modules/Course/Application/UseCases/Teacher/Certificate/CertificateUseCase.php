<?php

namespace App\Modules\Course\Application\UseCases\Teacher\Certificate;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Teacher\Certificate\CertificateDTO;
use App\Modules\Course\Http\Resources\Certificate\CertificateResource;
use App\Modules\Course\Application\DTOS\Teacher\Certificate\CertificateFilterDTO;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Certificate\CertificateRepository;

class CertificateUseCase
{

    protected $certificateRepository;
    protected $employee;


    public function __construct(CertificateRepository $certificateRepository)
    {
        $this->certificateRepository = $certificateRepository;
    }

    public function fetchCertificates(CertificateFilterDTO $certificateFilterDTO): DataStatus
    {
        try {
            // dd($certificateFilterDTO->toArray());
            $certificates = $this->certificateRepository->filter(
                dto: $certificateFilterDTO,
                operator: '=',
                translatableFields: ['title', 'about', 'requirements', 'target_audience', 'benefits'],
                paginate: $certificateFilterDTO->paginate,
                limit: $certificateFilterDTO->limit
            );
            $resource =  CertificateResource::collection($certificates);
            return DataSuccess(
                status: true,
                message: 'Certificates fetched successfully',
                data: $certificateFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchCertificateDetails(CertificateFilterDTO $certificateFilterDTO): DataStatus
    {
        try {
            $certificate = $this->certificateRepository->getById($certificateFilterDTO->certificate_id);
            $resource = new CertificateResource($certificate);
            return DataSuccess(
                status: true,
                message: 'Certificate fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createCertificate(CertificateDTO $certificateDTO): DataStatus
    {
        try {
            $certificate = $this->certificateRepository->create($certificateDTO);

            return DataSuccess(
                status: true,
                message: 'Certificate created successfully',
                data: new CertificateResource($certificate)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateCertificate(CertificateDTO $certificateDTO): DataStatus
    {
        try {
            $certificate = $this->certificateRepository->update($certificateDTO->certificate_id, $certificateDTO);
            return DataSuccess(
                status: true,
                message: ' Certificate updated successfully',
                data: new CertificateResource($certificate)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteCertificate(CertificateFilterDTO $certificateFilterDTO): DataStatus
    {
        try {
            $certificate = $this->certificateRepository->delete($certificateFilterDTO->certificate_id);
            return DataSuccess(
                status: true,
                message: ' Certificate deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function handelCertificateViewResource($certificates, $view = ViewTypeEnum::DASHBOARD->value)
    {
        if ($view == ViewTypeEnum::DASHBOARD->value) {
            return  CertificateResource::collection($certificates);
        } elseif ($view == ViewTypeEnum::WEBSITE->value) {
            return  CertificateResource::collection($certificates);
        } else {
            return  CertificateResource::collection($certificates);
        }
    }
}
