<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Employee\Http\Controllers\ApiConnect\Employee\EmployeeController;

Route::prefix('api/connect')->group(function () {

    Route::controller(EmployeeController::class)->group(function () {
        Route::post('fetch_employees', 'fetchEmployees');
    });
});
