<?php

namespace App\Modules\User\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Requests\Api\User\LoginRequest;
use App\Modules\User\Application\UseCases\User\UserUseCase;
use App\Modules\User\Http\Requests\Api\User\RegisterRequest;
use App\Modules\User\Http\Requests\Api\User\CheckCodeRequest;
use App\Modules\User\Http\Requests\Api\User\FetchUserRequest;
use App\Modules\User\Http\Requests\Global\User\UserIdRequest;
use App\Modules\User\Http\Requests\Global\User\UserIdsRequest;
use App\Modules\User\Http\Requests\Api\User\ResetPasswordRequest;
use App\Modules\User\Http\Requests\Api\User\CheckCredentialRequest;
use App\Modules\User\Http\Requests\Api\User\GetStageUserIdsRequest;
use App\Modules\User\Http\Requests\Api\User\CheckAuthenticationRequest;

class UserController extends Controller
{
    protected $userUseCase;

    public function __construct(UserUseCase $userUseCase)
    {
        $this->userUseCase = $userUseCase;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="User's full name", example="John Doe"),
     *             @OA\Property(property="username", type="string", nullable=true, description="User's username", example="johndoe"),
     *             @OA\Property(property="phone", type="string", description="User's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", description="User's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email address", example="john@example.com"),
     *             @OA\Property(property="password", type="string", description="User's password", example="secret123"),
     *             @OA\Property(property="device_token", type="string", nullable=true, description="Device token for push notifications", example="token123"),
     *             @OA\Property(property="device_id", type="string", nullable=true, description="Unique device identifier", example="device456"),
     *             @OA\Property(property="device_type", type="string", nullable=true, description="Type of device (e.g., iOS, Android)", example="Android"),
     *             @OA\Property(property="device_os", type="string", nullable=true, description="Device operating system", example="Android"),
     *             @OA\Property(property="device_os_version", type="string", nullable=true, description="Device OS version", example="14")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
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
        return $this->userUseCase->register($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Log in a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="User's email or phone", example="john@example.com"),
     *             @OA\Property(property="password", type="string", description="User's password", example="secret123"),
     *             @OA\Property(property="device_id", type="string", nullable=true, description="Unique device identifier", example="device456"),
     *             @OA\Property(property="device_token", type="string", nullable=true, description="Device token for push notifications", example="token123"),
     *             @OA\Property(property="device_type", type="string", nullable=true, description="Type of device (e.g., iOS, Android)", example="Android"),
     *             @OA\Property(property="device_os", type="string", nullable=true, description="Device operating system", example="Android"),
     *             @OA\Property(property="device_os_version", type="string", nullable=true, description="Device OS version", example="14")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
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
        return $this->userUseCase->login($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/check_credential",
     *     summary="Check if a credential (email/phone) is valid or exists",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="User's email or phone to check", example="john@example.com")
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
        return $this->userUseCase->checkCredential($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/check_code",
     *     summary="Verify a reset code for a credential",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="User's email or phone", example="john@example.com"),
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
        return $this->userUseCase->checkCode($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/reset_password",
     *     summary="Reset a user's password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="credential", type="string", description="User's email or phone", example="john@example.com"),
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
        return $this->userUseCase->resetPassword($request->toDTO())->response();
    }



    public function getStageUserIds(GetStageUserIdsRequest $request)
    {
        return $this->userUseCase->getStageUserIds($request->toDTO())->response();
    }

    public function getUsersDeviceTokens(UserIdsRequest $request)
    {
        return $this->userUseCase->getUsersDeviceTokens($request->toDTO())->response();
    }

    public function getAllUserIds()
    {
        return $this->userUseCase->getAllUserIds()->response();
    }
    public function get_user_by_id(UserIdRequest $request)
    {
        return $this->userUseCase->getUserById($request->toDTO())->response();
    }

    public function checkUserExists(UserIdRequest $request)
    {
        return $this->userUseCase->checkUserExists($request->toDTO())->response();
    }
    public function fetchUserDetails(UserIdRequest $request)
    {
        return $this->userUseCase->fetchUserDetails($request->toDTO())->response();
    }
    public function fetchUsers(FetchUserRequest $request)
    {
        return $this->userUseCase->fetchUsers($request->toDTO())->response();
    }
    public function checkAuthentication(CheckAuthenticationRequest $request)
    {
        return $this->userUseCase->checkAuthentication($request->toDTO())->response();
    }
}
