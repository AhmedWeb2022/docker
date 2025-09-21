<?php

namespace App\Modules\Diploma\Http\Controllers\Api\Diploma;


use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Diploma\Application\UseCases\Diploma\DiplomaUseCase;
use App\Modules\Diploma\Http\Requests\Dashboard\Diploma\FetchDiplomaRequest;
use App\Modules\Diploma\Http\Requests\Global\Diploma\DiplomaIdRequest;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;

class DiplomaController extends Controller
{
    protected $diplomaUseCase;

    public function __construct(DiplomaUseCase $diplomaUseCase)
    {
        $this->diplomaUseCase = $diplomaUseCase;
    }

    public function fetchDiplomas(FetchDiplomaRequest $request)
    {
        return $this->diplomaUseCase->fetchDiplomas($request->toDTO())->response();
    }
    public function fetchDiplomaDetails(DiplomaIdRequest $request)
    {
        return $this->diplomaUseCase->fetchDiplomaDetails($request->toDTO())->response();
    }

    public function fetchDiplomaContents(DiplomaIdRequest $request)
    {
        return $this->diplomaUseCase->fetchDiplomaDetails($request->toDTO())->response();
    }
}
