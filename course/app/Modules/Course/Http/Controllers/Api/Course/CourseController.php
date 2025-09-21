<?php

namespace App\Modules\Course\Http\Controllers\Api\Course;


use App\Http\Controllers\Controller;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Application\UseCases\Course\CourseUseCase;
use App\Modules\Course\Http\Requests\Global\Course\CourseIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\FetchCourseRequest;

class CourseController extends Controller
{
    protected $courseUseCase;

    public function __construct(CourseUseCase $courseUseCase)
    {
        $this->courseUseCase = $courseUseCase;
    }

    public function fetchCourses(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchCourses($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }
    public function FetchCoursesWithoutChildren(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchCourses($request->toDTO(), false)->response();
    }

    public function fetchCourseDetails(CourseIdRequest $request)
    {
        return $this->courseUseCase->fetchCourseDetails($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }

    public function fetchCourseContents(CourseIdRequest $request)
    {
        return $this->courseUseCase->fetchCourseDetails($request->toDTO(), ViewTypeEnum::WEBSITE->value, onlyContent: true)->response();
    }

    public function fetchTeacherCoursesResource(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchCourses($request->toDTO(), ViewTypeEnum::API->value)->response();
    }

    public function fetchTeacherCourses(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchCourses($request->toDTO(), ViewTypeEnum::MOBILE->value)->response();
    }

    public function fetchUserCourses(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchUserCourses($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }

    public function fetchUserFavoriteCourses(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchUserFavoriteCourses($request->toDTO(), ViewTypeEnum::WEBSITE->value)->response();
    }

    public function checkUserSubscribed(CourseIdRequest $request)
    {
        return $this->courseUseCase->checkSubscription($request->toDTO())->response();
    }

    public function fetchCourseStatistics(CourseIdRequest $request)
    {
        return $this->courseUseCase->fetchCourseStatistics($request->toDTO())->response();
    }
}
