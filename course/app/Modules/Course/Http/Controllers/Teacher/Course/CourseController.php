<?php

namespace App\Modules\Course\Http\Controllers\Teacher\Course;


use App\Http\Controllers\Controller;
use App\Modules\Course\Http\Requests\Teacher\Course\FetchCourseRequest;
use App\Modules\Course\Application\UseCases\Teacher\Course\CourseUseCase;
use App\Modules\Course\Http\Requests\Teacher\Course\FetchCourseDetailsRequest;

class CourseController extends Controller
{
    protected $courseUseCase;

    public function __construct(CourseUseCase $courseUseCase)
    {
        $this->courseUseCase = $courseUseCase;
    }

    public function fetchCourses(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchCourses($request->toDTO())->response();
    }

    public function fetchCourseDetails(FetchCourseDetailsRequest $request)
    {
        return $this->courseUseCase->fetchCourseDetails($request->toDTO())->response();
    }
}
