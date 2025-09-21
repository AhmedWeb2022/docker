<?php

namespace App\Modules\Course\Http\Controllers\Api\Content;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\UseCases\Content\ContentUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Content\FetchContentRequest;

class ContentController extends Controller
{
    protected $contentUseCase;

    public function __construct(ContentUseCase $contentUseCase)
    {
        $this->contentUseCase = $contentUseCase;
    }

    public function FetchContents(FetchContentRequest $request)
    {
        return $this->contentUseCase->fetchContents($request->toDTO())->response();
    }

    public function FetchContentDetails(FetchContentRequest $request)
    {
        // dd($request->toArray());
        return $this->contentUseCase->fetchContentDetails($request->toDTO() , ViewTypeEnum::WEBSITE->value)->response();
    }
}
