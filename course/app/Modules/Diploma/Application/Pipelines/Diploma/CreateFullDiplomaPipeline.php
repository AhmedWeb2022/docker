<?php
namespace App\Modules\Diploma\Application\Pipelines\Diploma;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaDTO;

use App\Modules\Diploma\Application\Pipelines\Steps\CreateContentCourseStep;
use App\Modules\Diploma\Application\Pipelines\Steps\CreateDiplomaStep;
use App\Modules\Diploma\Application\Pipelines\Steps\CreateIndependentTracksStep;
use App\Modules\Diploma\Application\Pipelines\Steps\CreateLevelsStep;
use App\Modules\Diploma\Application\Pipelines\Steps\CreateTracksStep;
use Illuminate\Pipeline\Pipeline;
class CreateFullDiplomaPipeline
{
    public static function run(DiplomaDTO $diplomaDTO)
    {
        return app(Pipeline::class)
            ->send($diplomaDTO)
            ->through([
                CreateDiplomaStep::class,
                CreateLevelsStep::class,
                CreateTracksStep::class,
                CreateIndependentTracksStep::class,
                CreateContentCourseStep::class,
            ])
            ->thenReturn();
    }
}
