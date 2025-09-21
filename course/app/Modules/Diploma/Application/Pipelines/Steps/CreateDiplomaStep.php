<?php
namespace App\Modules\Diploma\Application\Pipelines\Steps;
use App\Modules\Diploma\Application\UseCases\Diploma\DiplomaUseCase;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;

class CreateDiplomaStep
{
    public function handle($payload, \Closure $next)
    {
        $result = app(DiplomaUseCase::class)->createDiploma($payload);
        if (!$result->getStatus()) {
            throw new \Exception($result->getMessage());
        }
        /**
         * @var Diploma $result
         */
        $diplomaId = $result->getData()->id;
        // dd($result->getData()->id);
        $payload->diploma = Diploma::with([
            'translations',
            'targets',
            'abouts'
        ])->findOrFail($diplomaId);
        return $next($payload);
    }
}
