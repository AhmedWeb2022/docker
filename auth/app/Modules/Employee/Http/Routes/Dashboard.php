<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Employee\Http\Controllers\Dashboard\Employee\EmployeeController;

Route::prefix('dashboard/employee')->middleware('baseAuthMiddleware:admin')->group(function () {

    Route::controller(EmployeeController::class)->group(function () {
        Route::post('fetch_employees', 'fetchEmployees');
        Route::post('fetch_employee_details', 'fetchEmployeeDetails');
        Route::post('create_employee', 'createEmployee');
        Route::post('update_employee', 'updateEmployee');
        Route::post('delete_employee', 'deleteEmployee');
    });
});
