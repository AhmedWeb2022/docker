<?php

namespace App\Modules\Website\Http\Controllers\Dashboard\WebsiteSection;

use App\Modules\Website\Application\UseCases\WebsiteSection\WebsiteSectionUseCase;
use App\Modules\Website\Http\Requests\Dashboard\WebsiteSection\CreateWebsiteSectionRequest;
use App\Modules\Website\Http\Requests\Dashboard\WebsiteSection\FetchWebsiteSectionDetailsRequest;
use App\Modules\Website\Http\Requests\Dashboard\WebsiteSection\FetchWebsiteSectionRequest;
use App\Modules\Website\Http\Requests\Dashboard\WebsiteSection\UpdateWebsiteSectionRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebsiteSectionControllers extends Controller
{
    protected $websiteSectionUseCase;

    public function __construct(WebsiteSectionUseCase $websiteSectionUseCase)
    {
        $this->websiteSectionUseCase = $websiteSectionUseCase;
    }

    public function addWebsiteSections(CreateWebsiteSectionRequest $request)
    {
        return $this->websiteSectionUseCase->addWebsiteSections($request->toDTO())->response();
    }

    public function fetchWebsiteSection(FetchWebsiteSectionRequest $request)
    {
        return $this->websiteSectionUseCase->fetchWebsiteSection($request->toDTO())->response();
    }
        public function fetchWebsiteSectionDetails(FetchWebsiteSectionDetailsRequest $request)
    {
        return $this->websiteSectionUseCase->fetchWebsiteSectionDetails($request->toDTO())->response();
    }

    public function updateWebsiteSection(UpdateWebsiteSectionRequest $request)
    {
        return $this->websiteSectionUseCase->updateWebsiteSection($request->toDTO())->response();
    }

    // public function deleteLevel(LevelIdRequest $request)
    // {
    //     return $this->websiteSectionUseCase->deleteLevel($request->toDTO())->response();
    // }

}
