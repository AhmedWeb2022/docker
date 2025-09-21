<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Course\Http\Controllers\Teacher\Home\HomeController;
use App\Modules\Course\Http\Controllers\Teacher\Course\CourseController;
use App\Modules\Course\Http\Controllers\Teacher\Review\ReviewController;
use App\Modules\Course\Http\Controllers\Teacher\Certificate\CertificateController;
use App\Modules\Course\Http\Controllers\Teacher\Content\ContentController;
use App\Modules\Course\Http\Controllers\Teacher\Group\GroupController;

Route::prefix('teacher')->group(function () {

    /*************************************
     *Teacher Authinticated Routes
     *************************************
     */

    Route::middleware('baseAuthMiddleware:teacher')->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::post('fetch_courses', 'fetchCourses');
            Route::post('fetch_course_details', 'fetchCourseDetails');
        });
        Route::controller(HomeController::class)->group(function () {
            Route::get('fetch_statistics', 'fetchStatistics');
            Route::get('fetch_teacher_courses_user_ids', 'fetchTeacherCoursesUserIds');
        });
        Route::controller(ReviewController::class)->group(function () {
            Route::post('create_review', 'createReview');
            Route::post('fetch_review_details', 'fetchReviewDetails');
            Route::post('fetch_reviews', 'fetchReviews');
            Route::post('delete_review', 'deleteReview');
        });

        Route::controller(CertificateController::class)->group(function () {
            Route::post('fetch_certificates', 'fetchCertificates');
            Route::post('fetch_certificate_details', 'fetchCertificateDetails');
            Route::post('create_certificate', 'createCertificate');
            Route::post('update_certificate', 'updateCertificate');
            Route::post('delete_certificate', 'deleteCertificate');
        });

        Route::controller(GroupController::class)->group(function () {
            Route::post('fetch_groups', 'fetchGroups');
            Route::post('fetch_group_details', 'fetchGroupDetails');
        });

        Route::controller(ContentController::class)->group(function () {
            Route::post('fetch_contents', 'fetchContents');
            Route::post('fetch_content_details', 'fetchContentDetails');
        });
    });
});
