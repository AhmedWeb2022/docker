<?php

namespace App\Modules\Diploma\Application\UseCases\ContentCourse;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Diploma\Application\DTOS\DiplomaContentCourse\DiplomaContentCourseDTO;
use App\Modules\Diploma\Http\Resources\ContentCourse\ContentCourseResource;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaContentCourseRepository;

class DiplomaContentCourseUseCase
{
    protected $employee;
    protected $diplomaContentCourseRepository;

    public function __construct()
    {
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
        $this->diplomaContentCourseRepository = new DiplomaContentCourseRepository();
    }

    public function addContentCourses(DiplomaContentCourseDTO $diplomaContentCourseDTO): DataStatus
    {
        try {
            // dd($diplomaContentCourseDTO);
            // $addContentDTO->created_by = $this->employee->id;
            // dd($addContentDTO);
            $content = $this->diplomaContentCourseRepository->create($diplomaContentCourseDTO);
            return DataSuccess(
                status: true,
                message: 'Contents created successfully',
                data: new ContentCourseResource($content)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchContentCourseDiplomas(DiplomaContentCourseDTO $diplomaContentCourseDTO): DataStatus
    {
        try {
            // dd($diplomaContentCourseDTO);
            $contents = $this->diplomaContentCourseRepository->filter($diplomaContentCourseDTO);
            return DataSuccess(
                status: true,
                message: 'Contents fetched successfully',
                data: ContentCourseResource::collection($contents)->response()->getData()
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function deleteContentCourse(DiplomaContentCourseDTO $diplomaContentCourseDTO): DataStatus
    {
        try {
            $this->diplomaContentCourseRepository->delete($diplomaContentCourseDTO->content_course_id);
            return DataSuccess(
                status: true,
                message: 'Content deleted successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
