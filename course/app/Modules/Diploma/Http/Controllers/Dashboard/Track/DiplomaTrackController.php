<?php

namespace App\Modules\Diploma\Http\Controllers\Dashboard\Track;

use App\Modules\Diploma\Application\UseCases\Track\DiplomaTrackUseCase;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Diploma\Http\Requests\Dashboard\Track\CreateMultipleTracksRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Track\CreateSingleTrackRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Track\FetchTrackRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Track\UpdateTrackRequest;
use App\Modules\Diploma\Http\Requests\Global\Diploma\TrackIdRequest;

class DiplomaTrackController extends Controller
{
    protected $diplomaTrackUseCase;

    public function __construct(DiplomaTrackUseCase $diplomaTrackUseCase)
    {
        $this->diplomaTrackUseCase = $diplomaTrackUseCase;
    }

    public function addTrack(CreateSingleTrackRequest $request)
    {
        return $this->diplomaTrackUseCase->addTrack($request->toDTO())->response();
    }

    public function addTracks(CreateMultipleTracksRequest $request)
    {
        return $this->diplomaTrackUseCase->addTracks($request->toDTO())->response();
    }

    public function fetchTrackDiplomas(FetchTrackRequest $request)
    {
        return $this->diplomaTrackUseCase->fetchTrackDiplomas($request->toDTO())->response();
    }

    public function fetchTrackDetails(TrackIdRequest $request)
    {
        return $this->diplomaTrackUseCase->fetchTrackDetails($request->toDTO())->response();
    }

    public function updateTrack(UpdateTrackRequest $request)
    {
        return $this->diplomaTrackUseCase->updateTrack($request->toDTO())->response();
    }

    public function deleteTrack(TrackIdRequest $request)
    {
        return $this->diplomaTrackUseCase->deleteTrack($request->toDTO())->response();
    }

}
