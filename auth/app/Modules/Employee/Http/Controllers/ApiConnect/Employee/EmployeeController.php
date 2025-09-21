<?php

namespace App\Modules\Employee\Http\Controllers\ApiConnect\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Employee\Http\Requests\Api\Employee\LoginRequest;
use App\Modules\Employee\Http\Requests\ApiConnect\Employee\EmployeeRequest;
use App\Modules\Employee\Application\UseCases\ApiConnect\Employee\EmployeeUseCase;

class EmployeeController extends Controller
{
    protected $employeeUseCase;

    public function __construct(EmployeeUseCase $employeeUseCase)
    {
        $this->employeeUseCase = $employeeUseCase;
    }

    public function fetchEmployees(EmployeeRequest $request)
    {

        return $this->employeeUseCase->fetchEmployees($request->toDTO() , "api")->response();
    }
}
