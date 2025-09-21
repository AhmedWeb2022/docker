<?php

namespace App\Modules\Diploma\Http\Controllers\Dashboard\Level;

use App\Modules\Diploma\Application\UseCases\DiplomaLevel\DiplomaLevelUseCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Diploma\Application\UseCases\Content\DiplomaContentUseCase;
use App\Modules\Diploma\Application\UseCases\ContentCourse\DiplomaContentCourseUseCase;
use App\Modules\Diploma\Application\UseCases\DiplomaLevel\DiplomaLevelPipelineUseCase;
use App\Modules\Diploma\Application\UseCases\Track\DiplomaTrackUseCase;
use App\Modules\Diploma\Http\Requests\Dashboard\Level\CreateLevelRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Level\CreateLevelsRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Level\FetchLevelRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Level\UpdateLevelRequest;
use App\Modules\Diploma\Http\Requests\Global\Diploma\LevelIdRequest;
use Illuminate\Pipeline\Pipeline;
// use Illuminate\Support\Facades\Pipeline;

class DiplomaLevelController extends Controller
{
    protected $diplomaLevelUseCase;

    public function __construct(DiplomaLevelUseCase $diplomaLevelUseCase)
    {
        $this->diplomaLevelUseCase = $diplomaLevelUseCase;
    }

    public function addLevel(CreateLevelRequest $request)
    {
        return $this->diplomaLevelUseCase->addLevel($request->toDTO())->response();
    }

    public function addLevels(CreateLevelsRequest $request)
    {
        return $this->diplomaLevelUseCase->addLevels($request->toDTO())->response();
    }

    public function fetchLevelDiplomas(FetchLevelRequest $request)
    {
        return $this->diplomaLevelUseCase->fetchLevelDiplomas($request->toDTO())->response();
    }

    public function fetchDiplomaLevelDetails(LevelIdRequest $request)
    {
        return $this->diplomaLevelUseCase->fetchDiplomaLevelDetails($request->toDTO())->response();
    }

    public function updateLevel(UpdateLevelRequest $request)
    {
        return $this->diplomaLevelUseCase->updateLevel($request->toDTO())->response();
    }

    public function deleteLevel(LevelIdRequest $request)
    {
        return $this->diplomaLevelUseCase->deleteLevel($request->toDTO())->response();
    }
}
