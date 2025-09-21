<?php

namespace App\Modules\Course\Http\Controllers\Api\Certificate;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Certificate\CertificateUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Certificate\FetchCertificateRequest;
use App\Modules\Course\Http\Requests\Global\Certificate\CertificateIdRequest;

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
    public function fetchCertificatesResource(FetchCertificateRequest $request)
    {
        return $this->certificateUseCase->fetchCertificates($request->toDTO())->response();
    }
    public function fetchCertificateDetails(CertificateIdRequest $request)
    {
        return $this->certificateUseCase->fetchCertificateDetails($request->toDTO())->response();
    }
}
