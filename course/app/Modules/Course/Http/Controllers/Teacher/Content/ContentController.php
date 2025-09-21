<?php

namespace App\Modules\Course\Http\Controllers\Teacher\Content;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\UseCases\Teacher\Content\ContentUseCase;
use App\Modules\Course\Http\Requests\Teacher\Content\FetchContentRequest;

class ContentController extends Controller
{
    protected $contentUseCase;

    public function __construct(ContentUseCase $contentUseCase)
    {
        $this->contentUseCase = $contentUseCase;
    }

    public function FetchContents(FetchContentRequest $request)
    {
        return $this->contentUseCase->fetchContentsResource($request->toDTO())->response();
    }
}
