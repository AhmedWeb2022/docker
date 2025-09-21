<?php

namespace App\Modules\Course\Application\UseCases\Level;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Application\DTOS\Level\LevelDTO;
use App\Modules\Course\Http\Resources\Level\LevelResource;
use App\Modules\Course\Application\DTOS\Level\LevelFilterDTO;
use App\Modules\Course\Http\Resources\Level\FullLevelResource;
use App\Modules\Course\Http\Resources\Level\LevelWithContentResource;
use App\Modules\Course\Infrastructure\Persistence\Models\Level\Level;
use App\Modules\Course\Http\Resources\Level\LevelWithChildrenResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Level\LevelRepository;

class LevelUseCase
{

    protected $levelRepository;
    protected $employee;


    public function __construct(LevelRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchLevels(LevelFilterDTO $levelFilterDTO): DataStatus
    {
        try {
            $courseLevels = $this->levelRepository->getWhereHas(relation: 'courses',   key: 'course_id',
                value: $levelFilterDTO->course_id)->pluck('id')->toArray();
            $levels = $this->levelRepository->filter(
                dto: $levelFilterDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $levelFilterDTO->paginate,
                limit: $levelFilterDTO->limit                
            )->whereIn('id', $courseLevels);
            $resource =  LevelResource::collection($levels);
            return DataSuccess(
                status: true,
                message: 'Levels fetched successfully',
                data: $levelFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchLevelDetails(LevelFilterDTO $levelFilterDTO): DataStatus
    {
        try {
            $level = $this->levelRepository->getById($levelFilterDTO->level_id);
            $resource = new LevelResource($level);
            return DataSuccess(
                status: true,
                message: 'Level fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createLevel(LevelDTO $levelDTO): DataStatus
    {
        try {
            $level = $this->levelRepository->create($levelDTO);

            return DataSuccess(
                status: true,
                message: 'Level created successfully',
                data: new LevelResource($level)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updateLevel(LevelDTO $levelDTO): DataStatus
    {
        try {
            $level = $this->levelRepository->update($levelDTO->level_id, $levelDTO);
            return DataSuccess(
                status: true,
                message: ' Level updated successfully',
                data: new LevelResource($level)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteLevel(LevelFilterDTO $levelFilterDTO): DataStatus
    {
        try {
            $level = $this->levelRepository->delete($levelFilterDTO->level_id);
            return DataSuccess(
                status: true,
                message: ' Level deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
