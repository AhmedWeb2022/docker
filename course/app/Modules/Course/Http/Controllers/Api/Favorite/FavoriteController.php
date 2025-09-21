<?php

namespace App\Modules\Course\Http\Controllers\Api\Favorite;

use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Favorite\FavoriteUseCase;
use App\Modules\Course\Http\Requests\Api\Favorite\CreateFavoriteRequest;

class FavoriteController extends Controller
{
    protected $favoriteUseCase;

    public function __construct(FavoriteUseCase $favoriteUseCase)
    {
        $this->favoriteUseCase = $favoriteUseCase;
    }

    public function toggleFavorite(CreateFavoriteRequest $request)
    {
        return $this->favoriteUseCase->toggleFavorite($request->toDTO())->response();
    }
}
