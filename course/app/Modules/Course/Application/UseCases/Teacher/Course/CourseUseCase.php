<?php

namespace App\Modules\Course\Application\UseCases\Teacher\Course;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Teacher\Course\CourseDTO;
use App\Modules\Course\Http\Resources\Teacher\Course\CourseResource;
use App\Modules\Course\Http\Resources\Teacher\Course\CourseDetailsResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;


class CourseUseCase
{

    protected $courseRepository;
    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function fetchCourses(CourseDTO $courseDTO): DataStatus
    {
        try {
            // dd($courseDTO);
            $courses = $this->courseRepository->filter(
                $courseDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $courseDTO->paginate,
                limit: $courseDTO->limit,
            );
            // dd($courses); // Debugging line, can be removed later
            $resource = CourseResource::collection($courses);
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $courseDTO->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchHomeCourses(CourseDTO $courseDTO): DataStatus
    {
        try {
            $courses = $this->courseRepository->filter(
                $courseDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $courseDTO->paginate,
                limit: $courseDTO->limit,
                whereHasRelations: [
                    'courseTeachers' => [
                        'teacher_id' => $courseDTO->teacher_id
                    ]
                ]
            );
            $resource = CourseResource::collection($courses);
            return DataSuccess(
                status: true,
                message: 'Home courses fetched successfully',
                data: $courses
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function fetchCourseDetails(CourseDTO $courseDTO): DataStatus
    {
        try {
            $course = $this->courseRepository->getById($courseDTO->course_id);
            $resource = new CourseDetailsResource($course);
            return DataSuccess(
                status: true,
                message: 'Course fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
