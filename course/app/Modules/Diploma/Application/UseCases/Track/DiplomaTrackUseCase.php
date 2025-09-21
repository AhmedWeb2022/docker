<?php

namespace App\Modules\Diploma\Application\UseCases\Track;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Diploma\Application\DTOS\DiplomaContentCourse\DiplomaContentCourseDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use App\Modules\Diploma\Http\Resources\Track\DiplomaLevelTrackDetailsResource;
use App\Modules\Diploma\Http\Resources\Track\DiplomaTrackResource;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaLevelTrackRepository;

use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaContentCourseRepository;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaContentRepository;

class DiplomaTrackUseCase
{
    protected $employee;
    protected $diplomaTrackRepository;
    protected $diplomaContentRepository;
    protected $diplomaLevelContentCourseRepository;

    public function __construct()
    {
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
        $this->diplomaTrackRepository = new DiplomaLevelTrackRepository();
        $this->diplomaContentRepository = new DiplomaContentRepository();
        $this->diplomaLevelContentCourseRepository = new DiplomaContentCourseRepository();
    }

    public function addTrack(DiplomaLevelTrackDTO $diplomaLevelTrackDTO): DataStatus
    {
        try {
            // $diplomaLevelTrackDTO->created_by = $this->employee->id;
            $track = $this->diplomaTrackRepository->create($diplomaLevelTrackDTO);

            if (!empty($diplomaLevelTrackDTO->courses_ids) && is_array($diplomaLevelTrackDTO->courses_ids) && count($diplomaLevelTrackDTO->courses_ids) > 0) {
                $diplomaLevelTrackDTO->track_id = $track->id;
                // create contents directly
                $this->createContents($diplomaLevelTrackDTO->courses_ids, $diplomaLevelTrackDTO);
            }
            return DataSuccess(
                status: true,
                message: 'Tracks created successfully',
                data: new DiplomaTrackResource($track)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function createContents(array $coursesIds, $diplomaLevelDTO): array
    {
        $createdContents = [];
        foreach ($coursesIds as $courseId) {
            // $diplomaLevelContentDTO = DiplomaContentCourseDTO::fromArray($diplomaLevelDTO->toArray());
            // create diploma content
            $diplomaLevelContentDTO = new DiplomaContentCourseDTO($diplomaLevelDTO->toArray());
            if (isset($diplomaLevelDTO->track_id)) {
                $diplomaLevelContentDTO->diploma_level_track_id = $diplomaLevelDTO->track_id;
            }
            $diploma_content = $this->diplomaContentRepository->create($diplomaLevelContentDTO);
            // dd($diplomaLevelContentDTO->toArray(), $diploma_content);
            // create diploma content course
            $diplomaLevelContentDTO->course_id = $courseId;
            $diplomaLevelContentDTO->diploma_content_id = $diploma_content->id;
            $this->diplomaLevelContentCourseRepository->create($diplomaLevelContentDTO);
        }
        return $createdContents;
    }
    public function addTracks(DiplomaLevelTrackDTO $diplomaLevelTrackDTO): DataStatus
    {
        try {
            foreach ($diplomaLevelTrackDTO->tracks as $trackData) {
                $diplomaTrackDTO = DiplomaLevelTrackDTO::fromArray($trackData);
                $diplomaTrackDTO->diploma_id = $diplomaLevelTrackDTO->diploma_id;
                // dd($diplomaTrackDTO);
                $this->addTrack($diplomaTrackDTO);
            }
            return DataSuccess(
                status: true,
                message: 'Tracks created successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchTrackDiplomas(DiplomaLevelTrackDTO $diplomaTrackDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($diplomaTrackDTO);
            $tracks = $this->diplomaTrackRepository->filter($diplomaTrackDTO);
            $resource = $this->HandelCollectionResource(
                $tracks,
                $view,
            );
            return DataSuccess(
                status: true,
                message: 'Tracks fetched successfully',
                data: $diplomaTrackDTO->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function updateContents(array $coursesIds, DiplomaLevelTrackDTO $diplomaLevelDTO): array
    {
        $updatedContents = [];

        if (!isset($diplomaLevelDTO->track_id)) {
            return [];
        }

        $diplomaContentDTO = new DiplomaContentCourseDTO($diplomaLevelDTO->toArray());
        $diplomaContentDTO->diploma_level_track_id = $diplomaLevelDTO->track_id;

        $diplomaContent = $this->diplomaContentRepository->updateOrCreate($diplomaContentDTO);

        $existingContentCourse = $this->diplomaLevelContentCourseRepository->getMultibleWhere([
            'diploma_content_id' => $diplomaContent->id,
        ], 'get');
        if (!empty($existingContentCourse)) {
            // delete existing content course if it exists
            $existingContentCourse->each(function ($contentCourse) {
                $this->diplomaLevelContentCourseRepository->delete($contentCourse->id);
            });
        }
        foreach ($coursesIds as $courseId) {
            $courseDTO = new DiplomaContentCourseDTO([
                'diploma_content_id' => $diplomaContent->id,
                'course_id' => $courseId,
            ]);

            // dd($existingContentCourse);

            $updatedContent = $this->diplomaLevelContentCourseRepository->create($courseDTO);
            $updatedContents[] = $updatedContent;
        }

        return $updatedContents;
    }





    public function updateTrack(DiplomaLevelTrackDTO $diplomaLevelTrackDTO): DataStatus
    {
        try {
            $track = $this->diplomaTrackRepository->update(
                $diplomaLevelTrackDTO->track_id,
                $diplomaLevelTrackDTO
            );

            if (!empty($diplomaLevelTrackDTO->courses_ids) && is_array($diplomaLevelTrackDTO->courses_ids)) {
                $diplomaLevelTrackDTO->track_id = $track->id;
                $this->updateContents($diplomaLevelTrackDTO->courses_ids, $diplomaLevelTrackDTO);
            }

            return DataSuccess(
                status: true,
                message: 'Track updated successfully',
                data: new DiplomaTrackResource($track)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchTrackDetails(DiplomaLevelTrackDTO $diplomaTrackDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // $diplomaTrackDTO->updated_by = $this->employee->id;
            $track = $this->diplomaTrackRepository->getById($diplomaTrackDTO->track_id);
            $resource = $this->HandelDetailResource(
                $track,
                $view,
            );
            return DataSuccess(
                status: true,
                message: 'Track fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteTrack(DiplomaLevelTrackDTO $diplomaTrackDTO): DataStatus
    {
        try {
            $this->diplomaTrackRepository->delete($diplomaTrackDTO->track_id);
            return DataSuccess(
                status: true,
                message: 'Track deleted successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function HandelDetailResource($track, $viewType)
    {
        if ($viewType == ViewTypeEnum::WEBSITE->value) {
            return new DiplomaLevelTrackDetailsResource($track);
        } elseif ($viewType == ViewTypeEnum::DASHBOARD->value) {
            return new DiplomaLevelTrackDetailsResource($track);
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            return new DiplomaLevelTrackDetailsResource($track);
        }
    }

    private function HandelCollectionResource($tracks, $viewType)
    {
        if ($viewType == ViewTypeEnum::WEBSITE->value) {
            return DiplomaTrackResource::collection($tracks);
        } elseif ($viewType == ViewTypeEnum::DASHBOARD->value) {
            return DiplomaTrackResource::collection($tracks);
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            return DiplomaTrackResource::collection($tracks);
        }
    }

}
