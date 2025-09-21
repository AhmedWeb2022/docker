<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Http\Controllers\Dashboard\User\UserController;

Route::prefix('dashboard')->middleware('baseAuthMiddleware:admin')->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::post('fetch_users', 'fetchUsers');
        Route::post('fetch_user_details', 'fetchUserDetails');
        Route::post('create_user', 'createUser');
        Route::post('update_user', 'updateUser');
        Route::post('delete_user', 'deleteUser');
        Route::post('force_logout', 'forceLogout');
        Route::post('block_user', 'blockUser');
        Route::post('unblock_user', 'unblockUser');
    });
});
