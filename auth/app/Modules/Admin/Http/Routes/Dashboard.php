<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Http\Controllers\Dashboard\Admin\AdminController;

Route::prefix('dashboard/admin')->middleware('baseAuthMiddleware:admin')->group(function () {

    Route::controller(AdminController::class)->group(function () {
        Route::post('fetch_admins', 'fetchAdmins');
        Route::post('fetch_admin_details', 'fetchAdminDetails');
        Route::post('create_admin', 'createAdmin');
        Route::post('update_admin', 'updateAdmin');
        Route::post('delete_admin', 'deleteAdmin');
    });
});
