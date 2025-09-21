<?php

namespace App\Modules\Diploma\Application\Pipelines\Steps;

use App\Modules\Diploma\Application\DTOS\Diploma\AddLevelDTO;
use App\Modules\Diploma\Application\UseCases\DiplomaLevel\DiplomaLevelUseCase;

class CreateLevelsStep
{
public function handle($payload, \Closure $next)
{
    if (!$payload->has_level) {
        return $next($payload);
    }
    // dd($payload);
    foreach ($payload->levels ?? [] as $levelData) {
        $levelData['diploma_id'] = $payload->diploma->id ?? null;
        if (!isset($levelData['translations']) || !$levelData['diploma_id']) {
            throw new \InvalidArgumentException("Level data is missing required fields.");
        }
        // dd($levelData);
        $dto =  AddLevelDTO::fromArray($levelData);
        app(DiplomaLevelUseCase::class)->addLevel($dto);
    }
    return $next($payload);
}

}
