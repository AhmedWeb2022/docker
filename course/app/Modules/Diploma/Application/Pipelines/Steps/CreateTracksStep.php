<?php

namespace App\Modules\Diploma\Application\Pipelines\Steps;

use App\Modules\Diploma\Application\UseCases\Track\DiplomaTrackUseCase;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;

class CreateTracksStep
{
    public function handle($payload, \Closure $next)
    {
        if (!$payload->has_track) {
            return $next($payload);
        }

        foreach ($payload->tracks ?? [] as $trackData) {

            if (!isset($payload->diploma->id)) {
                throw new \InvalidArgumentException("Missing diploma ID for track creation.");
            }

            $trackData['diploma_id'] = $payload->diploma->id;

            $dto = DiplomaLevelTrackDTO::fromArray($trackData);

            app(DiplomaTrackUseCase::class)->addTracks($dto);
        }

        return $next($payload);
    }

}
