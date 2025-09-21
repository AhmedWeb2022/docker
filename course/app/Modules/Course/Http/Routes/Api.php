<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Base\Http\Middleware\AuthMiddleware;
use App\Modules\Course\Http\Controllers\Api\Rate\RateController;
use App\Modules\Course\Http\Controllers\Api\Course\CourseController;
use App\Modules\Course\Http\Controllers\Api\Lesson\LessonController;
use App\Modules\Course\Http\Controllers\Api\Content\ContentController;
use App\Modules\Course\Http\Controllers\Api\Partner\PartnerController;
use App\Modules\Course\Http\Controllers\Api\Favorite\FavoriteController;
use App\Modules\Course\Http\Controllers\Api\Certificate\CertificateController;
use App\Modules\Course\Http\Controllers\Api\ContentView\ContentViewController;
use App\Modules\Course\Http\Controllers\Api\Subscription\SubscriptionController;
use App\Modules\Course\Http\Controllers\Api\SubscribedClient\SubscribedClientController;
use App\Modules\Course\Infrastructure\Persistence\Models\Subscription\Subscription;

Route::prefix('api')->group(function () {

    Route::controller(CourseController::class)->group(function () {
        Route::post('fetch_courses', 'fetchCourses');
        Route::post('fetch_course_details', 'fetchCourseDetails');
        Route::post('fetch_teacher_courses', 'fetchTeacherCourses');
           Route::post('fetch_teacher_courses_resource', 'fetchTeacherCoursesResource');
        Route::post('fetch_course_contents', 'fetchCourseContents');
    });
    Route::controller(SubscribedClientController::class)->group(function () {
        Route::post('create_subscribed_client', 'createSubscribedClient');
    });
    Route::controller(LessonController::class)->group(function () {
        Route::post('fetch_lessons', 'fetchLessons');
    });
    Route::controller(PartnerController::class)->group(function () {
        Route::post('fetch_partners', 'fetchPartners');
        Route::post('fetch_partner_details', 'fetchPartnerDetails');
        Route::post('fetch_partner_courses', 'fetchPartnerCourses');
    });
    Route::controller(CertificateController::class)->group(function () {
        Route::post('fetch_certificates', 'fetchCertificates');
         Route::post('fetch_certificates_resource', 'fetchCertificatesResource');
        Route::post('fetch_certificate_details', 'fetchCertificateDetails');
    });


    /*************************************
     *User Authinticated Routes
     *************************************
     */

    Route::middleware('baseAuthMiddleware:user')->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::post('fetch_user_courses', 'fetchUserCourses');
            Route::post('fetch_user_favorite_courses', 'fetchUserFavoriteCourses');
            Route::post('check_user_subscribed', 'checkUserSubscribed');
            Route::post('fetch_course_statistics', 'fetchCourseStatistics');
        });
        Route::controller(RateController::class)->group(function () {
            Route::post('create_rate', 'createRate');
        });
        Route::controller(FavoriteController::class)->group(function () {
            Route::post('toggle_favorite', 'toggleFavorite');
            Route::post('delete_favorite', 'deleteFavorite');
            Route::post('fetch_favorites', 'fetchFavorites');
        });
        Route::controller(SubscriptionController::class)->group(function () {
            Route::post('subscribe', 'subscribe');
            Route::post('change_subscription_status', 'changeSubscriptionStatus');
            Route::post('get_user_ids', 'getUserIds');
            Route::post('check_subscription', 'checkSubscription');
        });
        Route::controller(ContentController::class)->group(function () {
            Route::post('fetch_contents', 'fetchContents');
            Route::post('fetch_content_details', 'fetchContentDetails');
        });
        Route::controller(ContentViewController::class)->group(function () {
            Route::post('create_content_view', 'createContentView');
            Route::post('fetch_content_views', 'fetchContentViews');
        });
    });




    
});
