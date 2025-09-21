<?php

use Illuminate\Support\Facades\Route;
use App\Modules\User\Http\Controllers\Dashboard\User\UserController;
use App\Modules\Course\Http\Controllers\Dashboard\Group\GroupController;
use App\Modules\Course\Http\Controllers\Dashboard\Level\LevelController;
use App\Modules\Course\Http\Controllers\Dashboard\Video\VideoController;
use App\Modules\Course\Http\Controllers\Dashboard\Course\CourseController;
use App\Modules\Course\Http\Controllers\Dashboard\Lesson\LessonController;
use App\Modules\Course\Infrastructure\Persistence\Models\Platform\Platform;
use App\Modules\Course\Http\Controllers\Dashboard\Content\ContentController;
use App\Modules\Course\Http\Controllers\Dashboard\Partner\PartnerController;
use App\Modules\Course\Http\Controllers\Dashboard\Currency\CurrencyController;
use App\Modules\Course\Http\Controllers\Dashboard\Platform\PlatformController;
use App\Modules\Course\Http\Controllers\Dashboard\Certificate\CertificateController;
use App\Modules\Course\Http\Controllers\Dashboard\Subscription\SubscriptionController;
use Illuminate\Support\Facades\Artisan;

// Route::get('delete_course_and_related_data', function () {
//     try {
//         Artisan::call('migrate:fresh');
//         return response()->json([
//             'message' => 'Database refreshed successfully',
//         ], 200);
//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'Error running migrate:fresh',
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// });
Route::prefix('dashboard')->group(function () {

    Route::middleware('baseAuthMiddleware:employee')->group(function () {

        Route::controller(CourseController::class)->group(function () {
            Route::post('fetch_courses', 'fetchCourses');
            Route::post('fetch_course_details', 'fetchCourseDetails');
            Route::post('fetch_course_detail', 'fetchCourseDetail');
            Route::post('create_course', 'createCourse');
            Route::post('update_course',    'updateCourse');
            Route::post('delete_course', 'deleteCourse');
            Route::post('add_levels', 'addLevels');
            Route::post('fetch_level_courses', 'fetchLevelCourses');
            Route::post('fetch_course_offers', 'fetchCourseOffers');
            Route::post("add_course_offer", 'addCourseOffer');
            Route::post("delete_course_offer", 'deleteCourseOffer');
            Route::post('toggle_favorite_course', 'toggleFavoriteCourse');
            Route::post('toggle_hidden_course', 'toggleHiddenCourse');
        });
        Route::controller(GroupController::class)->group(function () {
            Route::post('fetch_groups', 'fetchGroups');
            Route::post('fetch_group_details', 'fetchGroupDetails');
            Route::post('create_group', 'createGroup');
            Route::post('update_group', 'updateGroup');
            Route::post('delete_group', 'deleteGroup');
            Route::post('add_multiple_group_user', 'addMultipleGroupUser');
            Route::post('delete_multiple_group_user', 'deleteMultipleGroupUser');
        });
        Route::controller(ContentController::class)->group(function () {
            Route::post('fetch_contents', 'fetchContents');
            Route::post('fetch_content_details', 'fetchContentDetails');
            Route::post('create_content', 'createContent');
            Route::post('update_content', 'updateContent');
            Route::post('update_content_order', 'updateContentOrder');
            Route::post('delete_content', 'deleteContent');
        });
        Route::controller(SubscriptionController::class)->group(function () {
            Route::post('change_subscription_status', 'changeSubscriptionStatus');
            Route::post('fetch_subscriptions', 'fetchSubsciptions');
        });
        Route::controller(CertificateController::class)->group(function () {
            Route::post('fetch_certificates', 'fetchCertificates');
            Route::post('fetch_certificate_details', 'fetchCertificateDetails');
            Route::post('create_certificate', 'createCertificate');
            Route::post('update_certificate', 'updateCertificate');
            Route::post('delete_certificate', 'deleteCertificate');
        });
        Route::controller(VideoController::class)->group(function () {
            Route::post('fetch_videos', 'fetchVideos');
            Route::post('fetch_video_details', 'fetchVideoDetails');
            Route::post('create_video', 'createVideo');
            Route::post('update_video', 'updateVideo');
            Route::post('delete_video', 'deleteVideo');
        });
        Route::controller(LessonController::class)->group(function () {
            Route::post('fetch_lessons', 'fetchLessons');
            Route::post('fetch_lesson_details', 'fetchLessonDetails');
            Route::post('create_lesson', 'createLesson');
            Route::post('update_lesson', 'updateLesson');
            Route::post('delete_lesson', 'deleteLesson');
        });
        Route::controller(PartnerController::class)->group(function () {
            Route::post('fetch_partners', 'fetchPartners');
            Route::post('fetch_partner_details', 'fetchPartnerDetails');
            Route::post('create_partner', 'createPartner');
            Route::post('update_partner', 'updatePartner');
            Route::post('delete_partner', 'deletePartner');
        });
        Route::controller(LevelController::class)->group(function () {
            Route::post('fetch_levels', 'fetchLevels');
            Route::post('fetch_level_details', 'fetchLevelDetails');
            Route::post('create_level', 'createLevel');
            Route::post('update_level', 'updateLevel');
            Route::post('delete_level', 'deleteLevel');
            Route::post('fetch_level_courses', 'filterLevels');
        });
        Route::controller(PlatformController::class)->group(function () {
            Route::post('fetch_platforms', 'fetchPlatforms');
            Route::post('fetch_platform_details', 'fetchPlatformDetails');
            Route::post('create_platform', 'createPlatform');
            Route::post('update_platform', 'updatePlatform');
            Route::post('delete_platform', 'deletePlatform');
        });
        Route::controller(CurrencyController::class)->group(function () {
            Route::post('fetch_currencies', 'fetchCurrencies');
            Route::post('fetch_currency_details', 'fetchCurrencyDetails');
            Route::post('create_currency', 'createCurrency');
            Route::post('update_currency', 'updateCurrency');
            Route::post('delete_currency', 'deleteCurrency');
        });
    });
});
