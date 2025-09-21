<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Platform;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Platform\PlatformUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Platform\FetchPlatformRequest;
use App\Modules\Course\Http\Requests\Global\Platform\PlatformIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Platform\CreatePlatformRequest;
use App\Modules\Course\Http\Requests\Dashboard\Platform\UpdatePlatformRequest;

class PlatformController extends Controller
{
    protected $platformUseCase;

    public function __construct(PlatformUseCase $platformUseCase)
    {
        $this->platformUseCase = $platformUseCase;
    }

    public function fetchPlatforms(FetchPlatformRequest $request)
    {
        return $this->platformUseCase->fetchPlatforms($request->toDTO())->response();
    }

    public function fetchPlatformDetails(PlatformIdRequest $request)
    {
        return $this->platformUseCase->fetchPlatformDetails($request->toDTO())->response();
    }


    public function createPlatform(CreatePlatformRequest $request)
    {
        return $this->platformUseCase->createPlatform($request->toDTO())->response();
    }

    public function updatePlatform(UpdatePlatformRequest $request)
    {
        return $this->platformUseCase->updatePlatform($request->toDTO())->response();
    }


    public function deletePlatform(PlatformIdRequest $request)
    {
        return $this->platformUseCase->deletePlatform($request->toDTO())->response();
    }
}
