<?php

use App\Modules\Base\Http\Middleware\AuthMiddleware;
use App\Modules\Course\Http\Controllers\Api\Certificate\CertificateController;
use App\Modules\Diploma\Http\Controllers\Api\Diploma\DiplomaController;
use Illuminate\Support\Facades\Route;




Route::prefix('api')->group(function () {

    // Route::middleware('baseAuthMiddleware:employee')->group(function () {});


});
