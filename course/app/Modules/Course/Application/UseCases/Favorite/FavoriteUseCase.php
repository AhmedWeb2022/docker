<?php

namespace App\Modules\Course\Application\UseCases\Favorite;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Favorite\FavoriteDTO;
use App\Modules\Course\Http\Resources\Favorite\FavoriteResource;
use App\Modules\Course\Application\DTOS\Favorite\FavoriteFilterDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Favorite\FavoriteRepository;

class FavoriteUseCase
{

    protected $favoriteRepository;
    protected $employee;
    protected $user;

    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
        // $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
        $this->user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
    }

    public function fetchFavorites(FavoriteFilterDTO $favoriteFilterDTO): DataStatus
    {
        try {
            $courseFavorites = $this->favoriteRepository->getWhereHas(
                relation: 'courses',
                key: 'course_id',
                value: $favoriteFilterDTO->course_id
            )->pluck('id')->toArray();
            $favorites = $this->favoriteRepository->filter(
                dto: $favoriteFilterDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $favoriteFilterDTO->paginate,
                limit: $favoriteFilterDTO->limit
            )->whereIn('id', $courseFavorites);
            $resource =  FavoriteResource::collection($favorites);
            return DataSuccess(
                status: true,
                message: 'Favorites fetched successfully',
                data: $favoriteFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchFavoriteDetails(FavoriteFilterDTO $favoriteFilterDTO): DataStatus
    {
        try {
            $favorite = $this->favoriteRepository->getById($favoriteFilterDTO->favorite_id);
            $resource = new FavoriteResource($favorite);
            return DataSuccess(
                status: true,
                message: 'Favorite fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createFavorite(FavoriteDTO $favoriteDTO): DataStatus
    {
        try {
            // dd($favoriteDTO->toArray());
            $favorite = $this->favoriteRepository->create($favoriteDTO);

            return DataSuccess(
                status: true,
                message: 'Favorite created successfully',
                data: true //new FavoriteResource($favorite)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function toggleFavorite(FavoriteDTO $favoriteDTO): DataStatus
    {

        try {
            $favoriteDTO->user_id = $this->user->id;
            $favorite = $this->favoriteRepository->toggle([
                'user_id' => $this->user->id,
                'favoritable_id' => $favoriteDTO->favoritable_id,
                'favoritable_type' => $favoriteDTO->favoritable_type,
            ], $favoriteDTO);
            return DataSuccess(
                status: true,
                message: 'Favorite created successfully',
                data: true //new FavoriteResource($favorite)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateFavorite(FavoriteDTO $favoriteDTO): DataStatus
    {
        try {
            $favorite = $this->favoriteRepository->update($favoriteDTO->favorite_id, $favoriteDTO);
            return DataSuccess(
                status: true,
                message: ' Favorite updated successfully',
                data: new FavoriteResource($favorite)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteFavorite(FavoriteFilterDTO $favoriteFilterDTO): DataStatus
    {
        try {
            $favorite = $this->favoriteRepository->delete($favoriteFilterDTO->favorite_id);
            return DataSuccess(
                status: true,
                message: ' Favorite deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
