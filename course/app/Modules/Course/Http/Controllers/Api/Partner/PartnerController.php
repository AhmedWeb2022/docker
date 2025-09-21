<?php

namespace App\Modules\Course\Http\Controllers\Api\Partner;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\UseCases\Partner\PartnerUseCase;
use App\Modules\Course\Http\Requests\Global\Partner\PartnerIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Partner\FetchPartnerRequest;

class PartnerController extends Controller
{
    protected $partnerUseCase;

    public function __construct(PartnerUseCase $partnerUseCase)
    {
        $this->partnerUseCase = $partnerUseCase;
    }

    public function fetchPartners(FetchPartnerRequest $request)
    {
        return $this->partnerUseCase->fetchPartners($request->toDTO())->response();
    }

    public function fetchPartnerDetails(PartnerIdRequest $request)
    {
        return $this->partnerUseCase->fetchPartnerDetails($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }

    public function fetchPartnerCourses(PartnerIdRequest $request)
    {
        return $this->partnerUseCase->fetchPartnerCourses($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }
}
