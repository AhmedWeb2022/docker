<?php

namespace App\Modules\Course\Application\UseCases\Platform;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Application\DTOS\Platform\PlatformDTO;
use App\Modules\Course\Http\Resources\Platform\PlatformResource;
use App\Modules\Course\Application\DTOS\Platform\PlatformFilterDTO;
use App\Modules\Course\Http\Resources\Platform\FullPlatformResource;
use App\Modules\Course\Http\Resources\Platform\PlatformWithContentResource;
use App\Modules\Course\Infrastructure\Persistence\Models\Platform\Platform;
use App\Modules\Course\Http\Resources\Platform\PlatformWithChildrenResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Platform\PlatformRepository;

class PlatformUseCase
{

    protected $platformRepository;
    protected $employee;


    public function __construct(PlatformRepository $platformRepository)
    {
        $this->platformRepository = $platformRepository;
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchPlatforms(PlatformFilterDTO $platformFilterDTO): DataStatus
    {
        try {
            $platforms = $this->platformRepository->filter(
                dto: $platformFilterDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $platformFilterDTO->paginate,
                limit: $platformFilterDTO->limit
            );
            $resource =  PlatformResource::collection($platforms);
            return DataSuccess(
                status: true,
                message: 'Platforms fetched successfully',
                data: $platformFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchPlatformDetails(PlatformFilterDTO $platformFilterDTO): DataStatus
    {
        try {
            $platform = $this->platformRepository->getById($platformFilterDTO->platform_id);
            $resource = new PlatformResource($platform);
            return DataSuccess(
                status: true,
                message: 'Platform fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createPlatform(PlatformDTO $platformDTO): DataStatus
    {
        try {
            $platform = $this->platformRepository->create($platformDTO);
            return DataSuccess(
                status: true,
                message: 'Platform created successfully',
                data: new PlatformResource($platform)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function updatePlatform(PlatformDTO $platformDTO): DataStatus
    {
        try {
            $platform = $this->platformRepository->update($platformDTO->platform_id, $platformDTO);
            return DataSuccess(
                status: true,
                message: ' Platform updated successfully',
                data: new PlatformResource($platform)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deletePlatform(PlatformFilterDTO $platformFilterDTO): DataStatus
    {
        try {
            $platform = $this->platformRepository->delete($platformFilterDTO->platform_id);
            return DataSuccess(
                status: true,
                message: ' Platform deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
