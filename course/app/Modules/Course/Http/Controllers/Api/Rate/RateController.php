<?php

namespace App\Modules\Course\Http\Controllers\Api\Rate;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Rate\RateUseCase;
use App\Modules\Course\Http\Requests\Api\Rate\CreateRateRequest;

class RateController extends Controller
{
    protected $rateUseCase;

    public function __construct(RateUseCase $rateUseCase)
    {
        $this->rateUseCase = $rateUseCase;
    }

    public function createRate(CreateRateRequest $request)
    {
        return $this->rateUseCase->createRate($request->toDTO())->response();
    }
}
