<?php

namespace App\Modules\Diploma\Application\Pipelines\Steps;

use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use App\Modules\Diploma\Application\DTOS\Track\TrackDTO;
use App\Modules\Diploma\Application\UseCases\Track\DiplomaTrackUseCase;

class CreateIndependentTracksStep
{
    public function handle($payload, \Closure $next)
    {
        if (!$payload->has_level && $payload->has_track) {

            foreach ($payload->tracks ?? [] as $trackData) {

                if (!isset($payload->diploma->id)) {
                    throw new \InvalidArgumentException("Missing diploma ID for track creation.");
                }

                $trackData['diploma_id'] = $payload->diploma->id;

                $dto = DiplomaLevelTrackDTO::fromArray($trackData);

                app(DiplomaTrackUseCase::class)->addTracks($dto);
            }
        }

        return $next($payload);
    }
}

