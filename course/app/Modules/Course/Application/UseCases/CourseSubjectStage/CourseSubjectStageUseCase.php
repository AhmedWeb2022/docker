<?php

namespace App\Modules\Course\Application\UseCases\CourseSubjectStage;

use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\CourseSubjectStage\CourseSubjectStageDTO;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Http\Resources\CourseSubjectStage\CourseSubjectStageResource;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Application\DTOS\CourseSubjectStage\CourseSubjectStageFilterDTO;
use App\Modules\Course\Http\Resources\CourseSubjectStage\FullCourseSubjectStageResource;
use App\Modules\Course\Application\DTOS\CourseSubjectStageUser\CourseSubjectStageUserDTO;
use App\Modules\Course\Http\Resources\CourseSubjectStage\Api\CourseSubjectStageCourseResource;
use App\Modules\Course\Http\Resources\CourseSubjectStage\CourseSubjectStageWithContentResource;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseSubjectStage\CourseSubjectStage;
use App\Modules\Course\Http\Resources\CourseSubjectStage\CourseSubjectStageWithChildrenResource;
use App\Modules\Course\Http\Resources\CourseSubjectStage\Api\CourseSubjectStageDetailsFullResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\CourseSubjectStage\CourseSubjectStageRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\CourseSubjectStageUser\CourseSubjectStageUserRepository;

class CourseSubjectStageUseCase
{

    protected $courseSubjectStageRepository;
    /**
     *  @var Course
     */

    protected $course;


    public function __construct()
    {
        $this->courseSubjectStageRepository = new CourseSubjectStageRepository();
        // $this->course = CourseHolder::getCourse();
    }


    public function createCourseSubjectStage(CourseSubjectStageDTO $courseSubjectStageDTO): DataStatus
    {
        try {
            // dd($courseSubjectStageDTO);
            $courseSubjectStage = $this->courseSubjectStageRepository->create($courseSubjectStageDTO);
            // dd($courseSubjectStage);
            return DataSuccess(
                status: true,
                message: 'success',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateCourseSubjectStage(CourseSubjectStageDTO $courseSubjectStageDTO): DataStatus
    {
        try {
            $courseSubjectStage = $this->courseSubjectStageRepository->update($courseSubjectStageDTO->course_subject_stage_id, $courseSubjectStageDTO);

            return DataSuccess(
                status: true,
                message: 'success',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteCourseSubjectStage(CourseSubjectStageDTO $courseSubjectStageDTO): DataStatus
    {
        try {
            $courseSubjectStage = $this->courseSubjectStageRepository->delete($courseSubjectStageDTO->course_subject_stage_id);
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteAllCourseSubjectStageByCourse(CourseSubjectStageDTO $courseSubjectStageDTO): DataStatus
    {
        try {
            // dd($courseSubjectStageDTO);
            $courseSubjectStage = $this->courseSubjectStageRepository->getWhere('Course_id', $courseSubjectStageDTO->course_id);
            // dd($courseSubjectStage);
            $courseSubjectStage->each(function ($item) {
                $this->courseSubjectStageRepository->delete($item->id);
            });
            return DataSuccess(
                status: true,
                message: 'success',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
