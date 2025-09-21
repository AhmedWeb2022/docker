<?php

namespace App\Modules\Diploma\Application\UseCases\Diploma;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaDTO;
use App\Modules\Diploma\Application\DTOS\Diploma\DiplomaFilterDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaAbout\DiplomaAboutDTO;
use App\Modules\Diploma\Application\DTOS\DiplomaTarget\DiplomaTargetDTO;
use App\Modules\Diploma\Http\Resources\Diploma\DiplomaDetailsResource;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaLevelRepository;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\Diploma\DiplomaRepository;
use App\Modules\Diploma\Http\Resources\Diploma\DiplomaResource;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\DiplomaAbout\DiplomaAboutRepository;
use App\Modules\Diploma\Infrastructure\Persistence\Repositories\DiplomaTarget\DiplomaTargetRepository;

class DiplomaUseCase
{
    protected $employee;
    protected $diplomaRepository;
    protected $diplomaAboutRepository;
    protected $diplomaTargetRepository;
    protected $diplomaLevelRepository;

    public function __construct(DiplomaRepository $diplomaRepository)
    {
        $this->diplomaRepository = $diplomaRepository;

        $this->diplomaLevelRepository = new DiplomaLevelRepository();
        $this->diplomaAboutRepository = new DiplomaAboutRepository();
        $this->diplomaTargetRepository = new DiplomaTargetRepository();
    }

    public function fetchDiplomas(DiplomaFilterDTO $filterDTO): DataStatus
    {
        try {
            $diplomas = $this->diplomaRepository->filter(
                $filterDTO,
                operator: 'like',
                translatableFields: ['title', 'short_description', 'full_description'],
                paginate: $filterDTO->paginate,
                limit: $filterDTO->limit
            );
            $resource = DiplomaResource::collection($diplomas);
            return DataSuccess(
                status: true,
                message: 'Diplomas fetched successfully',
                data: $filterDTO->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchDiplomaDetails(DiplomaFilterDTO $filterDTO): DataStatus
    {
        try {
            $diploma = $this->diplomaRepository->getById($filterDTO->diploma_id);
            return DataSuccess(
                status: true,
                message: 'Diploma fetched successfully',
                data: new DiplomaDetailsResource($diploma)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function createDiploma(DiplomaDTO $diplomaDTO): DataStatus
    {
        try {
            $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
            $diplomaDTO->created_by = $this->employee->id;
            $diploma = $this->diplomaRepository->create($diplomaDTO);
            // handle the targets and abouts
            $diplomaDTO->diploma_id = $diploma->id;

            $this->createDiplomaDependencies($diplomaDTO);
            return DataSuccess(
                status: true,
                message: 'Diploma created successfully',
                data: $diploma,
                resourceData: new DiplomaResource($diploma)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function createDiplomaDependencies(DiplomaDTO $diplomaDTO): DataStatus
    {
        try {
            if (!empty($diplomaDTO->targets)) {
                foreach ($diplomaDTO->targets as $target) {
                    $targetDto = DiplomaTargetDTO::fromArray([
                        'diploma_id' => $diplomaDTO->diploma_id,
                        'translations' => $target['translations']
                    ]);
                    // dd($target, $targetDto->toArray());
                    $this->diplomaTargetRepository->create($targetDto);
                }
            }
            // :white_check_mark: add abouts if exists
            if (!empty($diplomaDTO->abouts)) {
                foreach ($diplomaDTO->abouts as $about) {
                    $this->diplomaAboutRepository->create(DiplomaAboutDTO::fromArray([
                        'diploma_id' => $diplomaDTO->diploma_id,
                        'translations' => $about['translations'],
                    ]));
                }
            }
            return DataSuccess(
                status: true,
                message: 'Diploma dependencies created successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function updateDiploma(DiplomaDTO $diplomaDTO): DataStatus
    {
        try {
            $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
            $diplomaDTO->updated_by = $this->employee->id;
            $diploma = $this->diplomaRepository->update($diplomaDTO->diploma_id, $diplomaDTO);
            $this->deleteAllDiplomaDependencies($diplomaDTO);
            $this->createDiplomaDependencies($diplomaDTO);
            return DataSuccess(
                status: true,
                message: 'Diploma updated successfully',
                data: new DiplomaResource($diploma)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteAllDiplomaDependencies(DiplomaDTO $diplomaDTO): DataStatus
    {
        try {
            if (!empty($diplomaDTO->targets)) {
                $this->diplomaTargetRepository->deleteByDiplomaId($diplomaDTO->diploma_id);
            }
            if (!empty($diplomaDTO->abouts)) {
                $this->diplomaAboutRepository->deleteByDiplomaId($diplomaDTO->diploma_id);
            }
            return DataSuccess(
                status: true,
                message: 'Diploma dependencies deleted successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function deleteDiploma(DiplomaFilterDTO $filterDTO): DataStatus
    {
        try {
            $this->diplomaRepository->delete($filterDTO->diploma_id);
            return DataSuccess(
                status: true,
                message: 'Diploma deleted successfully'
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
