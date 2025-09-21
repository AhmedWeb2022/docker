<?php

namespace App\Modules\Diploma\Http\Controllers\Dashboard\ContentCourse;

use App\Modules\Diploma\Application\UseCases\ContentCourse\DiplomaContentCourseUseCase;
use App\Modules\Diploma\Http\Requests\Global\Diploma\DiplomaContentCourseIdRequest;
use App\Http\Controllers\Controller;
use App\Modules\Diploma\Http\Requests\Dashboard\ContentCourse\CreateContentCourseRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\ContentCourse\FetchContentCourseRequest;
use Illuminate\Http\Request;


class DiplomaContentCourseController extends Controller
{
    protected $diplomaContentCourseUseCase;

    public function __construct(DiplomaContentCourseUseCase $diplomaContentCourseUseCase)
    {
        $this->diplomaContentCourseUseCase = $diplomaContentCourseUseCase;
    }

    public function addContentCourses(CreateContentCourseRequest $request)
    {
        return $this->diplomaContentCourseUseCase->addContentCourses($request->toDTO())->response();
    }

    public function fetchContentCourseDiplomas(FetchContentCourseRequest $request)
    {
        return $this->diplomaContentCourseUseCase->fetchContentCourseDiplomas($request->toDTO())->response();
    }

    public function deleteContentCourse(DiplomaContentCourseIdRequest $request)
    {
        return $this->diplomaContentCourseUseCase->deleteContentCourse($request->toDTO())->response();
    }

}
