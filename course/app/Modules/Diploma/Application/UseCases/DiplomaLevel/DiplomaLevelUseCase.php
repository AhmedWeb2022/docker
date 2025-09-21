<?php

namespace App\Modules\Diploma\Application\UseCases\DiplomaLevel;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Diploma\Application\DTOS\Diploma\AddLevelDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaContentCourse\DiplomaContentCourseDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevel\DiplomaLevelDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaLevelTrack\DiplomaLevelTrackDTO;
use App\Modules\Diploma\Http\Resources\Level\DiplomaLevelDetailsResource;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaLevelRepository;
use App\Modules\Diploma\Http\Resources\Level\DiplomaLevelResource;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaContentCourseRepository;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaContentRepository;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaLevelTrackRepository;
use Illuminate\Support\Facades\DB;

class DiplomaLevelUseCase
{
    protected $employee;
    protected $diplomaLevelRepository;
    protected $diplomaLevelTrackRepository;
    protected $diplomaContentRepository;
    protected $diplomaLevelContentCourseRepository;

    public function __construct()
    {
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
        $this->diplomaLevelRepository = new DiplomaLevelRepository();
        $this->diplomaLevelTrackRepository = new DiplomaLevelTrackRepository();
        $this->diplomaContentRepository = new DiplomaContentRepository();
        $this->diplomaLevelContentCourseRepository = new DiplomaContentCourseRepository();
    }

    public function addLevel(AddLevelDTO $addLevelDTO): DataStatus
    {
        try {
            DB::beginTransaction();
            // $addLevelDTO->created_by = $this->employee->id;
            //level
            $level = $this->diplomaLevelRepository->create($addLevelDTO);
            // then i level has_track == true, create tracks
            if ($addLevelDTO->has_track == true) {
                $addLevelDTO->level_id = $level->id;
                $this->createTracks($addLevelDTO->tracks, $addLevelDTO);
            } else {
                // if has_track is false, create contents directly
                $addLevelDTO->level_id = $level->id;
                $this->createContents($addLevelDTO->courses_ids, $addLevelDTO);
            }

            DB::commit();
            return DataSuccess(
                status: true,
                message: 'Levels created successfully',
                data: new DiplomaLevelResource($level)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    private function createTracks(array $tracks, AddLevelDTO $addLevelDTO): array
    {
        // dd($addLevelDTO);
        $createdTracks = [];
        foreach ($tracks as $track) {
            // $diplomaLevelTrackDTO = new DiplomaLevelTrackDTO($track);
            $diplomaLevelTrackDTO = DiplomaLevelTrackDTO::fromArray($track);
            $diplomaLevelTrackDTO->diploma_id = $addLevelDTO->diploma_id;
            $diplomaLevelTrackDTO->diploma_level_id = $addLevelDTO->level_id;

            $level_track = $this->diplomaLevelTrackRepository->create($diplomaLevelTrackDTO);
            $diplomaLevelTrackDTO->track_id = $level_track->id;
            // if track has contents, create them
            if (isset($track['courses_ids']) && is_array($track['courses_ids'])) {
                // dd($diplomaLevelTrackDTO);
                $this->createContents($track['courses_ids'], $diplomaLevelTrackDTO);
            }

        }
        return $createdTracks;
    }

    private function createContents(array $courses, $diplomaLevelDTO): array
    {
        $createdContents = [];
        foreach ($courses as $course) {
            // $diplomaLevelContentDTO = DiplomaContentCourseDTO::fromArray($diplomaLevelTrackDTO->toArray());
            // create diploma content
            $diplomaLevelContentDTO = new DiplomaContentCourseDTO($diplomaLevelDTO->toArray());
            if (isset($diplomaLevelDTO->track_id)) {
                $diplomaLevelContentDTO->diploma_level_track_id = $diplomaLevelDTO->track_id;
            }
            if (isset($diplomaLevelDTO->level_id) || isset($diplomaLevelDTO->diploma_level_id)) {
                // dd($diplomaLevelDTO->toArray());
                // if level_id is set, assign it to the content
                $diploma_level_id = $diplomaLevelDTO->level_id ?? $diplomaLevelDTO->diploma_level_id;
                $diplomaLevelContentDTO->diploma_level_id = $diploma_level_id;
            }
            $diploma_content = $this->diplomaContentRepository->create($diplomaLevelContentDTO);
            // dd($diplomaLevelContentDTO->toArray(), $diploma_content);
            // create diploma content course
            $diplomaLevelContentDTO->course_id = $course;
            $diplomaLevelContentDTO->diploma_content_id = $diploma_content->id;
            $this->diplomaLevelContentCourseRepository->create($diplomaLevelContentDTO);
        }
        return $createdContents;
    }
    public function addLevels(AddLevelDTO $addLevelDTO): DataStatus
    {
        try {
            foreach ($addLevelDTO->levels as $levelData) {
                // Create a new DTO for each level
                $levelDTO = AddLevelDTO::fromArray($levelData);
                $levelDTO->diploma_id = $addLevelDTO->diploma_id;
                // Call the addLevel method for each level
                $this->addLevel($levelDTO);
            }
            return DataSuccess(
                status: true,
                message: 'Levels created successfully',
                // data: DiplomaLevelResource::collection($collection)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchLevelDiplomas(DiplomaLevelDTO $diplomaLevelDTO): DataStatus
    {
        try {
            // dd($diplomaLevelDTO->toArray());
            // dd($diplomaLevelDTO);
            $levels = $this->diplomaLevelRepository->filter($diplomaLevelDTO);
            return DataSuccess(
                status: true,
                message: 'Levels fetched successfully',
                data: DiplomaLevelDetailsResource::collection($levels)->response()->getData()
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchDiplomaLevelDetails(DiplomaLevelDTO $diplomaLevelDTO): DataStatus
    {
        try {
            $diploma_level = $this->diplomaLevelRepository->getById($diplomaLevelDTO->level_id);
            return DataSuccess(
                status: true,
                message: 'Levels fetched successfully',
                data: DiplomaLevelDetailsResource::make($diploma_level)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLevel(AddLevelDTO $addLevelDTO): DataStatus
    {
        try {
            $level = $this->diplomaLevelRepository->update($addLevelDTO->level_id, $addLevelDTO);

            if ($addLevelDTO->has_track == true) {
                $this->diplomaLevelTrackRepository->deleteByLevelId($addLevelDTO->level_id);

                $addLevelDTO->level_id = $level->id;
                $addLevelDTO->diploma_id = $level->diploma_id;

                $this->createTracks($addLevelDTO->tracks, $addLevelDTO);

            } else {
                $this->diplomaContentRepository->deleteByLevelId($addLevelDTO->level_id);

                $addLevelDTO->level_id = $level->id;
                $this->createContents($addLevelDTO->courses_ids, $addLevelDTO);
            }

            return DataSuccess(
                status: true,
                message: 'Level updated successfully',
                data: new DiplomaLevelDetailsResource($level)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function deleteLevel(DiplomaLevelDTO $diplomaLevelDTO): DataStatus
    {
        try {
            $this->diplomaLevelRepository->delete($diplomaLevelDTO->level_id);
            return DataSuccess(
                status: true,
                message: 'Level deleted successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
