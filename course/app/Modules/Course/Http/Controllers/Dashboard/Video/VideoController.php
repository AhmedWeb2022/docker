<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Video;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Video\VideoUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Video\FetchVideoRequest;
use App\Modules\Course\Http\Requests\Global\Video\VideoIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Video\CreateVideoRequest;
use App\Modules\Course\Http\Requests\Dashboard\Video\UpdateVideoRequest;


class VideoController extends Controller
{
    protected $VideoUseCase;

    public function __construct(VideoUseCase $VideoUseCase)
    {
        $this->VideoUseCase = $VideoUseCase;
    }


    public function fetchVideos(FetchVideoRequest $request)
    {
        return $this->VideoUseCase->fetchVideos($request->toDTO())->response();
    }


    public function fetchVideoDetails(VideoIdRequest $request)
    {
        return $this->VideoUseCase->fetchVideoDetails($request->toDTO())->response();
    }


    public function createVideo(CreateVideoRequest $request)
    {
        return $this->VideoUseCase->createVideo($request->toDTO())->response();
    }

    public function deleteVideo(VideoIdRequest $request)
    {
        return $this->VideoUseCase->deleteVideo($request->toDTO())->response();
    }
}
