<?php

namespace App\Modules\Employee\Http\Controllers\Api\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Employee\Http\Requests\Api\Employee\LoginRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\EmployeeRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\RegisterRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\CheckCodeRequest;
use App\Modules\Employee\Application\UseCases\Employee\EmployeeUseCase;
use App\Modules\Employee\Http\Requests\Api\Employee\ResetPasswordRequest;
use App\Modules\Employee\Http\Requests\Global\Employee\EmployeeIdRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\CheckCredentialRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\CheckEmployeeExistRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\CheckAuthenticationRequest;

class EmployeeController extends Controller
{
    protected $employeeUseCase;

    public function __construct(EmployeeUseCase $employeeUseCase)
    {
        $this->employeeUseCase = $employeeUseCase;
    }

    /**
     * @OA\Post(
     *     path="/api/employee/register",
     *     summary="Register a new employee",
     *     tags={"Employee Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="Employee's full name", example="John Smith"),
     *             @OA\Property(property="username", type="string", nullable=true, description="Employee's username", example="johnsmith"),
     *             @OA\Property(property="phone", type="string", description="Employee's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", description="Employee's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", description="Employee's email address", example="john@example.com"),
     *             @OA\Property(property="password", type="string", description="Employee's password", example="secret123"),
     *             @OA\Property(property="device_token", type="string", nullable=true, description="Device token", example="token123"),
     *             @OA\Property(property="device_id", type="string", nullable=true, description="Device ID", example="device456"),
     *             @OA\Property(property="device_type", type="string", nullable=true, description="Device type", example="Android"),
     *             @OA\Property(property="device_os", type="string", nullable=true, description="Device OS", example="Android"),
     *             @OA\Property(property="device_os_version", type="string", nullable=true, description="Device OS version", example="14")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Employee registered successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or duplicate data (e.g., email/phone already exists)"
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        return $this->employeeUseCase->register($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/login",
     *     summary="Log in an employee",
     *     tags={"Employee Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Employee's email or phone", example="john@example.com"),
     *             @OA\Property(property="password", type="string", description="Employee's password", example="secret123"),
     *             @OA\Property(property="device_id", type="string", nullable=true, description="Device ID", example="device456"),
     *             @OA\Property(property="device_token", type="string", nullable=true, description="Device token", example="token123"),
     *             @OA\Property(property="device_type", type="string", nullable=true, description="Device type", example="Android"),
     *             @OA\Property(property="device_os", type="string", nullable=true, description="Device OS", example="Android"),
     *             @OA\Property(property="device_os_version", type="string", nullable=true, description="Device OS version", example="14")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Employee logged in successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        return $this->employeeUseCase->login($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/check_credential",
     *     summary="Check if an employee's credential (email/phone) is valid or exists",
     *     tags={"Employee Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Employee's email or phone", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Credential check successful",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credential format"
     *     )
     * )
     */
    public function checkCredential(CheckCredentialRequest $request)
    {
        return $this->employeeUseCase->checkCredential($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/check_code",
     *     summary="Verify a reset code for an employee's credential",
     *     tags={"Employee Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Employee's email or phone", example="john@example.com"),
     *             @OA\Property(property="code", type="string", description="Verification code", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Code verified successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credential or code"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Credential not found"
     *     )
     * )
     */
    public function checkCode(CheckCodeRequest $request)
    {
        return $this->employeeUseCase->checkCode($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/reset_password",
     *     summary="Reset an employee's password",
     *     tags={"Employee Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Employee's email or phone", example="john@example.com"),
     *             @OA\Property(property="code", type="string", description="Verification code", example="123456"),
     *             @OA\Property(property="password", type="string", description="New password", example="newsecret123", minLength=6)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credential, code, or password"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Credential not found"
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->employeeUseCase->resetPassword($request->toDTO())->response();
    }

    public function fetchEmployees(EmployeeRequest $request)
    {
        // dd($request->toDTO());
        return $this->employeeUseCase->fetchEmployees($request->toDTO(), "api")->response();
    }

    public function fetchEmployeeDetails(EmployeeIdRequest $request)
    {

        return $this->employeeUseCase->fetchEmployeeDetails($request->toDTO())->response();
    }

    public function checkEmployeeExists(CheckEmployeeExistRequest $request)
    {
        return $this->employeeUseCase->checkEmployeeExists($request->toDTO())->response();
    }

    public function checkAuthentication(CheckAuthenticationRequest $request)
    {
        return $this->employeeUseCase->checkAuthentication($request->toDTO())->response();
    }
}
