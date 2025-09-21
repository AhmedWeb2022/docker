<?php

namespace App\Modules\Diploma\Http\Controllers\Dashboard\Content;

use App\Modules\Diploma\Application\UseCases\Content\DiplomaContentUseCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Diploma\Http\Requests\Dashboard\Content\CreateContentRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Content\FetchContentRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Content\UpdateContentRequest;
use App\Modules\Diploma\Http\Requests\Global\Diploma\ContentIdRequest;

class DiplomaContentController extends Controller
{
    protected $diplomaContentUseCase;

    public function __construct(DiplomaContentUseCase $diplomaContentUseCase)
    {
        $this->diplomaContentUseCase = $diplomaContentUseCase;
    }

    public function addContents(CreateContentRequest $request)
    {
        return $this->diplomaContentUseCase->addContents($request->toDTO())->response();
    }

    public function fetchContentDiplomas(FetchContentRequest $request)
    {
        return $this->diplomaContentUseCase->fetchContentDiplomas($request->toDTO())->response();
    }

    // public function updateContent(UpdateContentRequest $request)
    // {
    //     return $this->diplomaContentUseCase->updateContent($request->toDTO())->response();
    // }

    public function deleteContent(ContentIdRequest $request)
    {
        return $this->diplomaContentUseCase->deleteContent($request->toDTO())->response();
    }

}
