<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Employee\Http\Controllers\Api\Employee\EmployeeController;
use App\Modules\Employee\Http\Controllers\Api\Employee\AuthEmployeeController;

Route::prefix('api/employee')->group(function () {

    Route::controller(EmployeeController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('check_credential', 'checkCredential');
        Route::post('check_code', 'checkCode');
        Route::post('reset_password', 'resetPassword');
        Route::post('fetch_employees', 'fetchEmployees');
        Route::post('check_employee_exists', 'checkEmployeeExists');
        Route::post('fetch_employee_details', 'fetchEmployeeDetails');
        Route::post('check_authentication', 'checkAuthentication');
    });
    Route::get('ping', function () {
        return response()->json(['status' => 'ok']);
    });
    Route::middleware('baseAuthMiddleware:employee')->group(function () {
        Route::controller(AuthEmployeeController::class)->group(function () {
            Route::post('logout', 'logout');
            Route::post('change_password', 'changePassword');
            Route::post('update_account', 'updateAccount');
            Route::post('delete_account', 'deleteAccount');

            Route::post('check_teacher_authentication', 'checkTeacherAuthentication');
            Route::post('fetch_teacher_students', 'fetchTeacherStudents');
        });
    });
});
