<?php

namespace App\Modules\Course\Http\Controllers\Api\ContentView;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\UseCases\ContentView\ContentViewUseCase;
use App\Modules\Course\Http\Requests\Api\ContentView\CreateContentViewRequest;


class ContentViewController extends Controller
{
    protected $contentViewUseCase;

    public function __construct(ContentViewUseCase $contentViewUseCase)
    {
        $this->contentViewUseCase = $contentViewUseCase;
    }

 public function createContentView(CreateContentViewRequest $request)
    {
        return $this->contentViewUseCase->createContentView($request->toDTO())->response();
    }
}
