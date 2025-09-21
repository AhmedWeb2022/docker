<?php

namespace App\Modules\Course\Http\Controllers\Teacher\Certificate;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Application\DTOS\Teacher\Certificate\CertificateDTO;
use App\Modules\Course\Http\Requests\Teacher\Certificate\CertificateIdRequest;
use App\Modules\Course\Http\Requests\Teacher\Certificate\FetchCertificateRequest;
use App\Modules\Course\Http\Requests\Teacher\Certificate\CreateCertificateRequest;
use App\Modules\Course\Http\Requests\Teacher\Certificate\UpdateCertificateRequest;
use App\Modules\Course\Application\UseCases\Teacher\Certificate\CertificateUseCase;

class CertificateController extends Controller
{
    protected $certificateUseCase;
    protected $teacher;

    public function __construct(CertificateUseCase $certificateUseCase)
    {
        $this->certificateUseCase = $certificateUseCase;
        $this->teacher = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::TEACHER->value);
    }

    public function fetchCertificates(FetchCertificateRequest $request)
    {
        return $this->certificateUseCase->fetchCertificates($request->toDTO())->response();
    }

    public function fetchCertificateDetails(CertificateIdRequest $request)
    {
        return $this->certificateUseCase->fetchCertificateDetails($request->toDTO())->response();
    }


    public function createCertificate(CreateCertificateRequest $request)
    {
        $data = $request->validated();
        $data['employee_id'] = $this->teacher->id;
        $certificateDTO =  CertificateDTO::fromArray($data);
        return $this->certificateUseCase->createCertificate($certificateDTO)->response();
    }

    public function updateCertificate(UpdateCertificateRequest $request)
    {
        return $this->certificateUseCase->updateCertificate($request->toDTO())->response();
    }


    public function deleteCertificate(CertificateIdRequest $request)
    {
        return $this->certificateUseCase->deleteCertificate($request->toDTO())->response();
    }
}
