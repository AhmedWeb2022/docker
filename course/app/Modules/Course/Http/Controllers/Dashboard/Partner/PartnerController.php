<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Partner;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Partner\PartnerUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Partner\FetchPartnerRequest;
use App\Modules\Course\Http\Requests\Global\Partner\PartnerIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Partner\CreatePartnerRequest;
use App\Modules\Course\Http\Requests\Dashboard\Partner\UpdatePartnerRequest;

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
        return $this->partnerUseCase->fetchPartnerDetails($request->toDTO())->response();
    }


    public function createPartner(CreatePartnerRequest $request)
    {
        return $this->partnerUseCase->createPartner($request->toDTO())->response();
    }

    public function updatePartner(UpdatePartnerRequest $request)
    {
        return $this->partnerUseCase->updatePartner($request->toDTO())->response();
    }


    public function deletePartner(PartnerIdRequest $request)
    {
        return $this->partnerUseCase->deletePartner($request->toDTO())->response();
    }
}
