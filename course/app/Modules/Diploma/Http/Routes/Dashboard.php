<?php

use App\Modules\Diploma\Http\Controllers\Dashboard\Content\DiplomaContentController;
use App\Modules\Diploma\Http\Controllers\Dashboard\ContentCourse\DiplomaContentCourseController;
use App\Modules\Diploma\Http\Controllers\Dashboard\Diploma\DiplomaController;
use App\Modules\Diploma\Http\Controllers\Dashboard\Diploma\V2\FullDiplomaController;
use App\Modules\Diploma\Http\Controllers\Dashboard\Faq\FaqController;
use App\Modules\Diploma\Http\Controllers\Dashboard\Level\DiplomaLevelController;
use App\Modules\Diploma\Http\Controllers\Dashboard\Track\DiplomaTrackController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->group(function () {
    Route::middleware('baseAuthMiddleware:employee')->group(function () {

        Route::controller(DiplomaController::class)->group(function () {
            Route::post('fetch_diplomas', 'fetchDiplomas');
            Route::post('fetch_diploma_details', 'fetchDiplomaDetails');
            Route::post('fetch_diploma_detail', 'fetchDiplomaDetail');
            Route::post('create_diploma', 'createDiploma');
            Route::post('update_diploma', 'updateDiploma');
            Route::post('delete_diploma', 'deleteDiploma');
        });

        //        Route for Diploma Level
        Route::controller(DiplomaLevelController::class)->group(function () {
            Route::post('add_level', 'addLevel');
            Route::post('add_levels', 'addLevels');
            Route::post('fetch_level_diploma', 'fetchLevelDiplomas');
            Route::post('fetch_diploma_level_details', 'fetchDiplomaLevelDetails');
            Route::post('update_diploma_level', 'updateLevel');
            Route::post('delete_diploma_level', 'deleteLevel');
        });
        //        Route for Diploma Track
        Route::controller(DiplomaTrackController::class)->group(function () {
            Route::post('add_track', 'addTrack');
            Route::post('add_tracks', 'addTracks');
            Route::post('fetch_track_diploma', 'fetchTrackDiplomas');
            Route::post('fetch_diploma_track_details', 'fetchTrackDetails');
            Route::post('update_track', 'updateTrack');
            Route::post('delete_track', 'deleteTrack');
        });
        //               Route for Diploma Content
        Route::controller(DiplomaContentController::class)->group(function () {
            Route::post('add_diploma_contents', 'addContents');
            Route::post('fetch_diploma_contents', 'fetchContentDiplomas');
            Route::post('delete_diploma_contents', 'deleteContent');
        });

        Route::controller(DiplomaContentCourseController::class)->group(function () {
            Route::post('add_diploma_contents_courses', 'addContentCourses');
            Route::post('fetch_diploma_contents_courses', 'fetchContentCourseDiplomas');
            Route::post('delete_diploma_contents_courses', 'deleteContentCourse');
        });

        Route::controller(FullDiplomaController::class)->group(function () {
            Route::post('create_full_diploma', 'createFullDiploma');
        });

        //        Faq
        Route::post('fetch_faqs_diplomas', [FaqController::class, 'fetchFaqs'])->name('fetch_faqs');
        Route::post('fetch_faq_diploma_details', [FaqController::class, 'fetchFaqDetails']);
        Route::post('create_faq_diploma', [FaqController::class, 'createFaq']);
        Route::post('update_faq_diploma', [FaqController::class, 'updateFaq']);
        Route::post('delete_faq_diploma', [FaqController::class, 'deleteFaq']);
    });
});
