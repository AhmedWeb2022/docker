<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Certificate;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Certificate\CertificateUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Certificate\FetchCertificateRequest;
use App\Modules\Course\Http\Requests\Global\Certificate\CertificateIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Certificate\CreateCertificateRequest;
use App\Modules\Course\Http\Requests\Dashboard\Certificate\UpdateCertificateRequest;

class CertificateController extends Controller
{
    protected $certificateUseCase;

    public function __construct(CertificateUseCase $certificateUseCase)
    {
        $this->certificateUseCase = $certificateUseCase;
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
        return $this->certificateUseCase->createCertificate($request->toDTO())->response();
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
