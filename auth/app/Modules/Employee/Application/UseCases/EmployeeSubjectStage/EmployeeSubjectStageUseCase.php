<?php

namespace App\Modules\Employee\Application\UseCases\EmployeeSubjectStage;





use App\Modules\Base\Application\Response\DataStatus;

use App\Modules\Employee\Application\DTOS\EmployeeSubjectStage\EmployeeSubjectStageDTO;
use App\Modules\Employee\Http\Resources\EmployeeSubjectStage\EmployeeSubjectStageResource;
use App\Modules\Employee\Infrastructure\Persistence\Repositories\EmployeeSubjectStage\EmployeeSubjectStageRepository;

class EmployeeSubjectStageUseCase
{

    protected $employeeSubjectStageRepository;
    /**
     *  @var Employee
     */

    protected $employee;


    public function __construct()
    {
        $this->employeeSubjectStageRepository = new EmployeeSubjectStageRepository();
        // $this->employee = EmployeeHolder::getEmployee();
    }


    public function createEmployeeSubjectStage(EmployeeSubjectStageDTO $employeeSubjectStageDTO): DataStatus
    {
        try {
            // dd($employeeSubjectStageDTO);
            $employeeSubjectStage = $this->employeeSubjectStageRepository->create($employeeSubjectStageDTO);
            // dd($employeeSubjectStage);
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

    public function updateEmployeeSubjectStage(EmployeeSubjectStageDTO $employeeSubjectStageDTO): DataStatus
    {
        try {
            $employeeSubjectStage = $this->employeeSubjectStageRepository->update($employeeSubjectStageDTO->employee_subject_stage_id, $employeeSubjectStageDTO);

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

    public function deleteEmployeeSubjectStage(EmployeeSubjectStageDTO $employeeSubjectStageDTO): DataStatus
    {
        try {
            $employeeSubjectStage = $this->employeeSubjectStageRepository->delete($employeeSubjectStageDTO->employee_subject_stage_id);
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

    public function deleteAllEmployeeSubjectStageByEmployee(EmployeeSubjectStageDTO $employeeSubjectStageDTO): DataStatus
    {
        try {
            // dd($employeeSubjectStageDTO);
            $employeeSubjectStage = $this->employeeSubjectStageRepository->getWhere('employee_id', $employeeSubjectStageDTO->employee_id);
            // dd($employeeSubjectStage);
            $employeeSubjectStage->each(function ($item) {
                $this->employeeSubjectStageRepository->delete($item->id);
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
