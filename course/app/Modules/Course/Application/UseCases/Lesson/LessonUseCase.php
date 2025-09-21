<?php

namespace App\Modules\Course\Application\UseCases\Lesson;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Application\DTOS\Lesson\LessonDTO;
use App\Modules\Course\Http\Resources\Lesson\LessonResource;
use App\Modules\Course\Application\DTOS\Lesson\LessonFilterDTO;
use App\Modules\Course\Http\Resources\Lesson\FullLessonResource;
use App\Modules\Course\Http\Resources\Lesson\LessonWithContentResource;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Course\Http\Resources\Lesson\LessonWithChildrenResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Lesson\LessonRepository;

class LessonUseCase
{

    protected $lessonRepository;
    protected $videoRepository;
    protected $employee;


    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
        $this->videoRepository = new VideoRepository();
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchLessons(LessonFilterDTO $lessonFilterDTO, $withChild = false): DataStatus
    {
        try {

            $lessons = $this->lessonRepository->filter(
                $lessonFilterDTO,
                operator: 'like',
                translatableFields: ['title'],
                paginate: $lessonFilterDTO->paginate,
                limit: $lessonFilterDTO->limit
            );
            $resource =  $this->handelLessonResource($lessonFilterDTO, $lessons, $lessonFilterDTO->paginate, true);

            return DataSuccess(
                status: true,
                message: 'lessons fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchLessonDetails(LessonFilterDTO $lessonFilterDTO): DataStatus
    {
        try {
            $lesson = $this->lessonRepository->getById($lessonFilterDTO->lesson_id);
            $resource = $this->handelLessonResource($lessonFilterDTO, $lesson);
            return DataSuccess(
                status: true,
                message: 'Course Lesson fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createLesson(LessonDTO $lessonDTO): DataStatus
    {
        try {
            // dd($lessonDTO);
            $lessonDTO->created_by = $this->employee->id;
            $lesson = $this->lessonRepository->create($lessonDTO);
            if ($lessonDTO->video != null) {
                /** @var VideoDTO $videoDTO */
                $videoDTO = VideoDTO::fromArray($lessonDTO->video);
                $videoDTO->videoable_id = $lesson->id;
                $videoDTO->videoable_type = Lesson::class;
                $video = $this->videoRepository->create($videoDTO);
            }
            $lesson->refresh();
            return DataSuccess(
                status: true,
                message: 'Course Lesson created successfully',
                data: new LessonResource($lesson)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLesson(LessonDTO $lessonDTO): DataStatus
    {
        try {
            $lessonDTO->updated_by = $this->employee->id;
            $lesson = $this->lessonRepository->update($lessonDTO->lesson_id, $lessonDTO);
            return DataSuccess(
                status: true,
                message: 'Course Lesson updated successfully',
                data: new LessonResource($lesson)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteLesson(LessonFilterDTO $lessonFilterDTO): DataStatus
    {
        try {
            $lesson = $this->lessonRepository->delete($lessonFilterDTO->lesson_id);
            return DataSuccess(
                status: true,
                message: 'Course Lesson deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function handelLessonResource($dto, $lesson, $paginate = false, $collection = false)
    {
        $resource = null;
        if ($dto->with_contents && !$dto->with_children) {
            $resource = $collection ? LessonWithContentResource::collection($lesson) : new LessonWithContentResource($lesson);
        } elseif ($dto->with_children && !$dto->with_contents) {
            $resource = $collection ? LessonWithChildrenResource::collection($lesson) : new LessonWithChildrenResource($lesson);
        } elseif ($dto->with_contents && $dto->with_children) {
            $resource = $collection ? FullLessonResource::collection($lesson) : new FullLessonResource($lesson);
        } else {
            $resource = $collection ? LessonResource::collection($lesson) : new LessonResource($lesson);
        }
        return $paginate ? $resource->response()->getData(true) : $resource;
    }
}
