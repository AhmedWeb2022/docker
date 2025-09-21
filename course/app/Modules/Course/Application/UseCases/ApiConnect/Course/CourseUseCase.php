<?php

namespace App\Modules\Course\Application\UseCases\ApiConnect\Course;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\ApiConnect\Course\CheckTeacherHasCourseDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;
use App\Modules\Course\Application\DTOS\ApiConnect\Course\CheckStageSubjectHasCourseDTO;
use App\Modules\Course\Application\DTOS\ApiConnect\Course\FetchTeacherCourseStudentsDTO;


class CourseUseCase
{

    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function checkTeacherHasCourse(CheckTeacherHasCourseDTO $checkTeacherHasCourseDTO): DataStatus
    {
        try {
            // dd($checkTeacherHasCourseDTO);
            $courses = $this->courseRepository->filter(
                dto: $checkTeacherHasCourseDTO,
                whereHasRelations: [
                    'courseTeachers' => [
                        'teacher_id' => $checkTeacherHasCourseDTO->teacher_id
                    ]
                ]
            );
            // dd($courses);
            $hasCourse = $courses->count() > 0 ? true : false;
            return DataSuccess(
                status: true,
                message: 'Check teacher has course successfully',
                data: $hasCourse
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function checkStageSubjectHasCourse(CheckStageSubjectHasCourseDTO $checkStageSubjectHasCourseDTO): DataStatus
    {
        try {
            // dd($checkStageSubjectHasCourseDTO);
            $courses = $this->courseRepository->filter(
                dto: $checkStageSubjectHasCourseDTO,
                whereHasRelations: [
                    'courseSubjectStages' => [
                        'subject_stage_id' => $checkStageSubjectHasCourseDTO->subject_stage_id
                    ]
                ]
            );

            $hasCourse = $courses->count() > 0 ? true : false;
            return DataSuccess(
                status: true,
                message: 'Check stage subject has course successfully',
                data: $hasCourse
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchTeacherCourseStudents(FetchTeacherCourseStudentsDTO $fetchTeacherCourseStudentsDTO): DataStatus
    {
        try {
            $courses = $this->courseRepository->filter(
                dto: $fetchTeacherCourseStudentsDTO,
                whereHasRelations: [
                    'courseTeachers' => [
                        'teacher_id' => $fetchTeacherCourseStudentsDTO->teacher_id
                    ],
                    'subscriptions'
                ]
            );
            $subscriptions = $courses->map(function ($course) {
                return $course->subscriptions;
            })->flatten()->unique(function ($item) {
                return $item->user_id;
            })->count();
            return DataSuccess(
                status: true,
                message: 'Fetch teacher course students successfully',
                data: $subscriptions
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
