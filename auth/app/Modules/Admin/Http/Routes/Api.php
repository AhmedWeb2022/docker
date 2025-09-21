<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Http\Controllers\Api\Admin\AdminController;
use App\Modules\Admin\Http\Controllers\Api\Admin\AuthAdminController;

Route::prefix('api/admin')->group(function () {

    Route::controller(AdminController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('check_credential', 'checkCredential');
        Route::post('check_code', 'checkCode');
        Route::post('reset_password', 'resetPassword');
    });

    Route::middleware('baseAuthMiddleware:admin')->group(function () {
        Route::controller(AuthAdminController::class)->group(function () {
            Route::post('logout', 'logout');
            Route::post('change_password', 'changePassword');
            Route::post('update_account', 'updateAccount');
            Route::post('delete_account', 'deleteAccount');
        });
    });
});
