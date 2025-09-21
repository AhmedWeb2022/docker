<?php

namespace App\Modules\Admin\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Http\Requests\Api\Admin\LoginRequest;
use App\Modules\Admin\Http\Requests\Api\Admin\RegisterRequest;
use App\Modules\Admin\Http\Requests\Api\Admin\CheckCodeRequest;
use App\Modules\Admin\Application\UseCases\Admin\AdminUseCase;
use App\Modules\Admin\Http\Requests\Api\Admin\ResetPasswordRequest;
use App\Modules\Admin\Http\Requests\Api\Admin\CheckCredentialRequest;

class AdminController extends Controller
{
    protected $adminUseCase;

    public function __construct(AdminUseCase $adminUseCase)
    {
        $this->adminUseCase = $adminUseCase;
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/register",
     *     summary="Register a new Admin",
     *     tags={"Admin Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="Admin's full name", example="Jane Doe"),
     *             @OA\Property(property="username", type="string", nullable=true, description="Admin's username", example="janedoe"),
     *             @OA\Property(property="phone", type="string", description="Admin's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", description="Admin's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", description="Admin's email address", example="jane@example.com"),
     *             @OA\Property(property="password", type="string", description="Admin's password", example="secret123"),
     *             @OA\Property(property="device_token", type="string", nullable=true, description="Device token", example="token123"),
     *             @OA\Property(property="device_id", type="string", nullable=true, description="Device ID", example="device456"),
     *             @OA\Property(property="device_type", type="string", nullable=true, description="Device type", example="Android"),
     *             @OA\Property(property="device_os", type="string", nullable=true, description="Device OS", example="Android"),
     *             @OA\Property(property="device_os_version", type="string", nullable=true, description="Device OS version", example="14")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Admin registered successfully",
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
        return $this->adminUseCase->register($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/login",
     *     summary="Log in a Admin",
     *     tags={"Admin Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Admin's email or phone", example="jane@example.com"),
     *             @OA\Property(property="password", type="string", description="Admin's password", example="secret123"),
     *             @OA\Property(property="device_id", type="string", nullable=true, description="Device ID", example="device456"),
     *             @OA\Property(property="device_token", type="string", nullable=true, description="Device token", example="token123"),
     *             @OA\Property(property="device_type", type="string", nullable=true, description="Device type", example="Android"),
     *             @OA\Property(property="device_os", type="string", nullable=true, description="Device OS", example="Android"),
     *             @OA\Property(property="device_os_version", type="string", nullable=true, description="Device OS version", example="14")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin logged in successfully",
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
        return $this->adminUseCase->login($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/check_credential",
     *     summary="Check if a Admin's credential (email/phone) is valid or exists",
     *     tags={"Admin Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Admin's email or phone", example="jane@example.com")
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
        return $this->adminUseCase->checkCredential($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/check_code",
     *     summary="Verify a reset code for a Admin's credential",
     *     tags={"Admin Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Admin's email or phone", example="jane@example.com"),
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
        return $this->adminUseCase->checkCode($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/reset_password",
     *     summary="Reset a Admin's password",
     *     tags={"Admin Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="Admin's email or phone", example="jane@example.com"),
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
        return $this->adminUseCase->resetPassword($request->toDTO())->response();
    }
}
