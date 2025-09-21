<?php

namespace App\Modules\Course\Http\Controllers\ApiConnect\Course;


use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\ApiConnect\Course\CourseUseCase;
use App\Modules\Course\Http\Requests\ApiConnect\Course\CheckTeacherHasCourseRequest;
use App\Modules\Course\Http\Requests\ApiConnect\Course\CheckStageSubjectHasCourseRequest;
use App\Modules\Course\Http\Requests\ApiConnect\Course\FetchTeacherCourseStudentsRequest;

class CourseController extends Controller
{
    protected $courseUseCase;
    protected $subscriptionUseCase;

    public function __construct(CourseUseCase $courseUseCase)
    {
        $this->courseUseCase = $courseUseCase;
        // $this->subscriptionUseCase = new SubscriptionUSe
    }

    public function checkTeacherHasCourse(CheckTeacherHasCourseRequest $request)
    {
        return $this->courseUseCase->checkTeacherHasCourse($request->toDTO())->response();
    }

    public function fetchTeacherCourseStudents(FetchTeacherCourseStudentsRequest $request)
    {
        return  $this->courseUseCase->fetchTeacherCourseStudents($request->toDTO())->response();
    }

    public function checkStageSubjectHasCourse(CheckStageSubjectHasCourseRequest $request)
    {
        return $this->courseUseCase->checkStageSubjectHasCourse($request->toDTO())->response();
    }
}
