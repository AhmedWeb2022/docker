<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Currency;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Currency\CurrencyUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Currency\FetchCurrencyRequest;
use App\Modules\Course\Http\Requests\Global\Currency\CurrencyIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Currency\CreateCurrencyRequest;
use App\Modules\Course\Http\Requests\Dashboard\Currency\UpdateCurrencyRequest;

class CurrencyController extends Controller
{
    protected $currencyUseCase;

    public function __construct(CurrencyUseCase $currencyUseCase)
    {
        $this->currencyUseCase = $currencyUseCase;
    }

    public function fetchCurrencies(FetchCurrencyRequest $request)
    {
        return $this->currencyUseCase->fetchCurrencies($request->toDTO())->response();
    }

    public function fetchCurrencyDetails(CurrencyIdRequest $request)
    {
        return $this->currencyUseCase->fetchCurrencyDetails($request->toDTO())->response();
    }


    public function createCurrency(CreateCurrencyRequest $request)
    {
        return $this->currencyUseCase->createCurrency($request->toDTO())->response();
    }

    public function updateCurrency(UpdateCurrencyRequest $request)
    {
        return $this->currencyUseCase->updateCurrency($request->toDTO())->response();
    }


    public function deleteCurrency(CurrencyIdRequest $request)
    {
        return $this->currencyUseCase->deleteCurrency($request->toDTO())->response();
    }
}
