<?php

use App\Modules\Base\Http\Middleware\AuthMiddleware;
use App\Modules\Diploma\Http\Controllers\Api\Diploma\DiplomaController;
use App\Modules\Diploma\Http\Controllers\Website\Faq\FaqController;
use Illuminate\Support\Facades\Route;




Route::prefix('api')->group(function () {

    // Route::middleware('baseAuthMiddleware:employee')->group(function () {});
    Route::controller(DiplomaController::class)->group(function () {
        Route::post('fetch_diplomas', 'fetchDiplomas');
        Route::post('fetch_diploma_details', 'fetchDiplomaDetails');
        Route::post('fetch_diploma_contents', 'fetchDiplomaContents');
    });


    Route::controller(FaqController::class)->group(function () {
        Route::post('fetch_fq', 'fetchFaqs');
    });
});
