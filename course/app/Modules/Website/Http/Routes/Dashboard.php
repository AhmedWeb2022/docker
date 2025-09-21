<?php

use App\Modules\Website\Http\Controllers\Dashboard\WebsiteSection\WebsiteSectionControllers;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::middleware('baseAuthMiddleware:employee')->group(function () {

        Route::controller(WebsiteSectionControllers::class)->group(function () {
            Route::post('fetch_website_section', 'fetchWebsiteSection');
            Route::post('create_website_section', 'addWebsiteSections');
            Route::post('update_website_section', 'updateWebsiteSection');
            Route::post('fetch_website_section_details','fetchWebsiteSectionDetails');
        });
    });
});
