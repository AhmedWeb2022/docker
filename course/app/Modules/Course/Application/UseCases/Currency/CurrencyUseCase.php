<?php

namespace App\Modules\Course\Application\UseCases\Currency;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Application\DTOS\Currency\CurrencyDTO;
use App\Modules\Course\Http\Resources\Currency\CurrencyResource;
use App\Modules\Course\Application\DTOS\Currency\CurrencyFilterDTO;
use App\Modules\Course\Http\Resources\Currency\FullCurrencyResource;
use App\Modules\Course\Http\Resources\Currency\CurrencyWithContentResource;
use App\Modules\Course\Infrastructure\Persistence\Models\Currency\Currency;
use App\Modules\Course\Http\Resources\Currency\CurrencyWithChildrenResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Currency\CurrencyRepository;

class CurrencyUseCase
{

    protected $currencyRepository;
    protected $employee;


    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function fetchCurrencies(CurrencyFilterDTO $currencyFilterDTO): DataStatus
    {
        try {
            $currencys = $this->currencyRepository->filter(
                dto: $currencyFilterDTO,
                operator: 'like',
                translatableFields: ['title'],
                paginate: $currencyFilterDTO->paginate,
                limit: $currencyFilterDTO->limit
            );
            $resource =  CurrencyResource::collection($currencys);
            return DataSuccess(
                status: true,
                message: 'Currencys fetched successfully',
                data: $currencyFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchCurrencyDetails(CurrencyFilterDTO $currencyFilterDTO): DataStatus
    {
        try {
            $currency = $this->currencyRepository->getById($currencyFilterDTO->currency_id);
            $resource = new CurrencyResource($currency);
            return DataSuccess(
                status: true,
                message: 'Currency fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createCurrency(CurrencyDTO $currencyDTO): DataStatus
    {
        try {
            $currency = $this->currencyRepository->create($currencyDTO);

            return DataSuccess(
                status: true,
                message: 'Currency created successfully',
                data: new CurrencyResource($currency)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateCurrency(CurrencyDTO $currencyDTO): DataStatus
    {
        try {
            $currency = $this->currencyRepository->update($currencyDTO->currency_id, $currencyDTO);
            return DataSuccess(
                status: true,
                message: ' Currency updated successfully',
                data: new CurrencyResource($currency)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteCurrency(CurrencyFilterDTO $currencyFilterDTO): DataStatus
    {
        try {
            $currency = $this->currencyRepository->delete($currencyFilterDTO->currency_id);
            return DataSuccess(
                status: true,
                message: ' Currency deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
