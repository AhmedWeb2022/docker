<?php

namespace App\Modules\Diploma\Http\Controllers\Dashboard\Diploma\V2;

use App\Http\Controllers\Controller;
use App\Modules\Diploma\Application\UseCases\Diploma\DiplomaUseCase;
use App\Modules\Diploma\Http\Requests\Dashboard\Diploma\CreateFullDiplomaRequest;
use App\Modules\Diploma\Application\Pipelines\Diploma\CreateFullDiplomaPipeline;
use App\Modules\Base\Application\Response\DataSuccess;
use App\Modules\Diploma\Http\Resources\Diploma\DiplomaDetailsResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FullDiplomaController extends Controller
{
    public function __construct(
        protected DiplomaUseCase $useCase
    ) {
    }

    public function createFullDiploma(CreateFullDiplomaRequest $request): JsonResponse
    {
        try {
            // 1. Transform request into DTO
            $diplomaDTO = $request->toDTO();

            // 2. Pass through the pipeline to handle full creation logic
            $response = CreateFullDiplomaPipeline::run($diplomaDTO);
            $res = new DiplomaDetailsResource($response->diploma);
            // 3. Return using DataSuccess
            return (new DataSuccess(resourceData: $res))->response();

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
