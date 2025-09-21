<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Http\Controllers\Api\User\UserController;
use App\Modules\User\Http\Controllers\Api\User\AuthUserController;

Route::prefix('api')->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('check_credential', 'checkCredential');
        Route::post('check_code', 'checkCode');
        Route::post('reset_password', 'resetPassword');
        Route::post('get_user_by_id', 'get_user_by_id');
        Route::post('get_stage_user_ids', 'getStageUserIds');
        Route::post('get_users_device_tokens', 'getUsersDeviceTokens');
        Route::get('get_all_user_ids', 'getAllUserIds');
        Route::post('check_user_exists', 'checkUserExists');
        Route::post('fetch_users', 'fetchUsers');
        Route::post('fetch_user_details', 'fetchUserDetails');
        Route::post('check_authentication', 'checkAuthentication');
    });

    Route::middleware('baseAuthMiddleware:user')->group(function () {
        Route::controller(AuthUserController::class)->group(function () {
            Route::post('logout', 'logout');
            Route::post('change_password', 'changePassword');
            Route::post('update_account', 'updateAccount');
            Route::post('update_image', 'updateImage');
            Route::post('delete_account', 'deleteAccount');
        });
    });
});
