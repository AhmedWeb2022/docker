<?php

namespace App\Modules\Employee\Http\Controllers\Dashboard\Employee;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Employee\Application\DTOS\Social\SocialDTO;
use App\Modules\Employee\Application\UseCases\Social\SocialUseCase;
use App\Modules\Employee\Application\UseCases\Employee\EmployeeUseCase;
use App\Modules\Employee\Http\Requests\Global\Employee\EmployeeIdRequest;
use App\Modules\Employee\Http\Requests\Dashboard\Employee\FetchEmployeeRequest;
use App\Modules\Employee\Http\Requests\Dashboard\Employee\CreateEmployeeRequest;
use App\Modules\Employee\Http\Requests\Dashboard\Employee\UpdateEmployeeRequest;
use App\Modules\Employee\Http\Requests\Dashboard\Employee\FetchEmployeeDetailsRequest;
use App\Modules\Employee\Application\DTOS\EmployeeSubjectStage\EmployeeSubjectStageDTO;
use App\Modules\Employee\Application\UseCases\EmployeeSubjectStage\EmployeeSubjectStageUseCase;

class EmployeeController extends Controller
{
    protected $employeeUseCase;
    protected $socialUseCase;
    protected $employeeSubjectStageUseCase;

    public function __construct(EmployeeUseCase $employeeUseCase)
    {
        $this->employeeUseCase = $employeeUseCase;
        $this->socialUseCase = new SocialUseCase();
        $this->employeeSubjectStageUseCase = new EmployeeSubjectStageUseCase();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_employees",
     *     summary="Fetch a list of employees",
     *     tags={"Dashboard Employee"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", nullable=true, description="Filter by employee name", example="John"),
     *             @OA\Property(property="email", type="string", format="email", nullable=true, description="Filter by employee email", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of employees",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchEmployees(FetchEmployeeRequest $request)
    {

        return $this->employeeUseCase->fetchEmployees($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_employee_details",
     *     summary="Fetch details of a specific employee",
     *     tags={"Dashboard Employee"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="employee_id", type="integer", description="The ID of the employee", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid employee ID"
     *     )
     * )
     */
    public function fetchEmployeeDetails(EmployeeIdRequest $request)
    {
        return $this->employeeUseCase->fetchEmployeeDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_employee",
     *     summary="Create a new employee",
     *     tags={"Dashboard Employee"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="Employee's full name", example="John Smith"),
     *             @OA\Property(property="username", type="string", nullable=true, description="Employee's username", example="johnsmith"),
     *             @OA\Property(property="phone", type="string", description="Employee's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", description="Employee's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", description="Employee's email address", example="john@example.com"),
     *             @OA\Property(property="password", type="string", description="Employee's password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Employee created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or duplicate data (e.g., email/phone already exists)"
     *     )
     * )
     */
    public function createEmployee(CreateEmployeeRequest $request)
    {
        $employeeDTO = $request->toDTO();
        /** @var Employee $employee */
        $employee = $this->employeeUseCase->createEmployee($employeeDTO)->getData();


        $employeeDTO->employee_id = $employee->id;
        /** @var SocialDTO $socialDto */
        $employeeDTO->socials['employee_id'] = $employee->id;

        $socialDto = SocialDTO::fromArray($employeeDTO->socials ?? []);

        $this->socialUseCase->createSocial($socialDto);
        if (isset($employeeDTO->subject_stage_ids) && is_array($employeeDTO->subject_stage_ids) && count($employeeDTO->subject_stage_ids) > 0) {

            foreach ($employeeDTO->subject_stage_ids as $subject_stage_id) {
                $employeeSubjectStageDTO = EmployeeSubjectStageDTO::fromArray([
                    'employee_id' => $employee->id,
                    'subject_stage_id' => $subject_stage_id
                ]);
                $this->employeeSubjectStageUseCase->createEmployeeSubjectStage($employeeSubjectStageDTO);
            }
        }
        return $this->employeeUseCase->fetchEmployeeDetails($employeeDTO)->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_employee",
     *     summary="Update an existing employee",
     *     tags={"Dashboard Employee"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="employee_id", type="integer", description="The ID of the employee to update", example=1),
     *             @OA\Property(property="name", type="string", nullable=true, description="Employee's full name", example="John Smith"),
     *             @OA\Property(property="username", type="string", nullable=true, description="Employee's username", example="johnsmith"),
     *             @OA\Property(property="phone", type="string", nullable=true, description="Employee's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", nullable=true, description="Employee's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", nullable=true, description="Employee's email address", example="john@example.com"),
     *             @OA\Property(property="password", type="string", nullable=true, description="Employee's new password", example="newsecret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or duplicate data (e.g., email/phone already exists)"
     *     )
     * )
     */
    public function updateEmployee(UpdateEmployeeRequest $request)
    {
        $employeeDTO = $request->toDTO();
        /** @var Employee $employee */
        $employee = $this->employeeUseCase->updateEmployee($employeeDTO)->getData();
        // dd($employeeDTO->subject_stage_ids);
        if (isset($employeeDTO->subject_stage_ids) && is_array($employeeDTO->subject_stage_ids) && count($employeeDTO->subject_stage_ids) > 0) {
            /** @var EmployeeSubjectStageDTO $employeeSubjectStageDTO */
            $employeeSubjectStageDTO = EmployeeSubjectStageDTO::fromArray([
                'employee_id' => $employee->id,
            ]);
            $this->employeeSubjectStageUseCase->deleteAllEmployeeSubjectStageByEmployee($employeeSubjectStageDTO);
            foreach ($employeeDTO->subject_stage_ids as $subject_stage_id) {
                $employeeSubjectStageDTO->subject_stage_id = $subject_stage_id;
                $this->employeeSubjectStageUseCase->createEmployeeSubjectStage($employeeSubjectStageDTO);
            }
        }

        return $this->employeeUseCase->fetchEmployeeDetails($employeeDTO)->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_employee",
     *     summary="Delete an employee",
     *     tags={"Dashboard Employee"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="employee_id", type="integer", description="The ID of the employee to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Employee deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Employee not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid employee ID"
     *     )
     * )
     */
    public function deleteEmployee(EmployeeIdRequest $request)
    {
        return $this->employeeUseCase->deleteEmployee($request->toDTO())->response();
    }
}
