<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Level;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Level\LevelUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Level\FetchLevelRequest;
use App\Modules\Course\Http\Requests\Global\Level\LevelIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Level\CreateLevelRequest;
use App\Modules\Course\Http\Requests\Dashboard\Level\FilterLevelRequest;
use App\Modules\Course\Http\Requests\Dashboard\Level\UpdateLevelRequest;

class LevelController extends Controller
{
    protected $levelUseCase;

    public function __construct(LevelUseCase $levelUseCase)
    {
        $this->levelUseCase = $levelUseCase;
    }

    public function fetchLevels(FetchLevelRequest $request)
    {
        return $this->levelUseCase->fetchLevels($request->toDTO())->response();
    }
    
    public function filterLevels(FilterLevelRequest $request)
    {
        return $this->levelUseCase->fetchLevels($request->toDTO())->response();
    }

    public function fetchLevelDetails(LevelIdRequest $request)
    {
        return $this->levelUseCase->fetchLevelDetails($request->toDTO())->response();
    }


    public function createLevel(CreateLevelRequest $request)
    {
        return $this->levelUseCase->createLevel($request->toDTO())->response();
    }

    public function updateLevel(UpdateLevelRequest $request)
    {
        return $this->levelUseCase->updateLevel($request->toDTO())->response();
    }


    public function deleteLevel(LevelIdRequest $request)
    {
        return $this->levelUseCase->deleteLevel($request->toDTO())->response();
    }
}
