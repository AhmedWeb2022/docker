<?php

namespace App\Modules\Diploma\Application\Pipelines\Steps;

use App\Modules\Diploma\Application\DTOS\DiplomaContent\DiplomaContentDTO;
use App\Modules\Diploma\Application\UseCases\Content\DiplomaContentUseCase;

class CreateContentCourseStep
{
    public function handle($payload, \Closure $next)
    {
        if ($payload->has_level || $payload->has_track) {
            return $next($payload);
        }

        foreach ($payload->contents ?? [] as $contentData) {
            $contentData['diploma_id'] = $payload->diploma->id;

            $dto =  DiplomaContentDTO::fromArray($contentData);

            app(DiplomaContentUseCase::class)->addContents($dto);
        }

        return $next($payload);
    }

}
