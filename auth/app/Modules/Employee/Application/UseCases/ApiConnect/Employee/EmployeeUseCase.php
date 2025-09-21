<?php

namespace App\Modules\Employee\Application\UseCases\ApiConnect\Employee;




use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Employee\Http\Resources\ApiConnect\Employee\EmployeeResource;
use App\Modules\Employee\Application\DTOS\ApiConnect\Employee\EmployeeFilterDTO;
use App\Modules\Employee\Infrastructure\Persistence\Repositories\Employee\EmployeeRepository;


class EmployeeUseCase
{

    protected $employeeRepository;
    /**
     *  @var Employee
     */

    protected $employee;


    public function __construct()
    {
        $this->employeeRepository = new EmployeeRepository();
        // $this->employee = EmployeeHolder::getEmployee();
    }

    public function fetchEmployees(EmployeeFilterDTO $employeeFilterDTO, $view = 'dashboard'): DataStatus
    {
        try {
            // dd($employeeFilterDTO->toArray());
            $employees = $this->employeeRepository->filter(
                dto: $employeeFilterDTO,
                paginate: $employeeFilterDTO->paginate,
                searchableFields: ['name', 'email', 'phone'],
                limit: $employeeFilterDTO->limit,
            );
            $resource = EmployeeResource::collection($employees);
            return DataSuccess(
                status: true,
                message: 'success',
                data: $employeeFilterDTO->paginate ? $resource->response()->getData(true) : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
