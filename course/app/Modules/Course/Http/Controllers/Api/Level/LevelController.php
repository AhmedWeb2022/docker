<?php

namespace App\Modules\Course\Http\Controllers\Api\Level;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Level\LevelUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Level\FetchLevelRequest;

class LevelController extends Controller
{
    protected $levelUseCase;

    public function __construct(LevelUseCase $levelUseCase)
    {
        $this->levelUseCase = $levelUseCase;
    }

    public function FetchLevels(FetchLevelRequest $request)
    {
        return $this->levelUseCase->fetchLevels($request->toDTO())->response();
    }
}
