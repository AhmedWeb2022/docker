<?php

namespace App\Modules\User\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Requests\Api\User\LogoutRequest;
use App\Modules\User\Application\UseCases\User\AuthUserUseCase;
use App\Modules\User\Http\Requests\Api\User\UpdateImageRequest;
use App\Modules\User\Http\Requests\Api\User\UpdateAccountRequest;
use App\Modules\User\Http\Requests\Api\User\ChangePasswordRequest;
use App\Modules\User\Http\Requests\Api\User\CheckAuthenticationRequest;

class AuthUserController extends Controller
{
    protected $userUseCase;

    public function __construct(AuthUserUseCase $userUseCase)
    {
        $this->userUseCase = $userUseCase;
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Log out the authenticated user",
     *     tags={"Authenticated User"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="device_token", type="string", nullable=true, description="Device token for push notifications", example="token123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication required"
     *     )
     * )
     */
    public function logout(LogoutRequest $request)
    {
        return $this->userUseCase->logout($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/change_password",
     *     summary="Change the authenticated user's password",
     *     tags={"Authenticated User"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="old_password", type="string", description="Current password", example="secret123"),
     *             @OA\Property(property="new_password", type="string", description="New password", example="newsecret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid old password or bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication required"
     *     )
     * )
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->userUseCase->changePassword($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/update_account",
     *     summary="Update the authenticated user's account details",
     *     tags={"Authenticated User"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", nullable=true, description="User's full name", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Account updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication required"
     *     )
     * )
     */
    public function updateAccount(UpdateAccountRequest $request)
    {
        return $this->userUseCase->updateAccount($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/delete_account",
     *     summary="Delete the authenticated user's account",
     *     tags={"Authenticated User"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=204,
     *         description="Account deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication required"
     *     )
     * )
     */
    public function deleteAccount()
    {
        return $this->userUseCase->deleteAccount()->response();
    }

    public function checkAuthentication(CheckAuthenticationRequest $request)
    {
        return $this->userUseCase->checkAuthentication($request->toDTO())->response();
    }

    public function updateImage(UpdateImageRequest $request)
    {
        return $this->userUseCase->updateImage($request->toDTO())->response();
    }
}
