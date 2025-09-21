<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Course\Http\Controllers\ApiConnect\Course\CourseController;

Route::prefix('api')->group(function () {

    Route::controller(CourseController::class)->group(function () {
        Route::post('check_teacher_has_course', 'checkTeacherHasCourse');
          Route::post('check_stage_subject_has_course', 'checkStageSubjectHasCourse');
        Route::post('fetch_teacher_course_students', 'fetchTeacherCourseStudents');
    });



    /*************************************
     *User Authinticated Routes
     *************************************
     */

    Route::middleware('baseAuthMiddleware:user')->group(function () {

    });





});
