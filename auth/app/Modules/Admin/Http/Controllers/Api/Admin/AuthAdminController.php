<?php

namespace App\Modules\Admin\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Http\Requests\Api\Admin\LogoutRequest;
use App\Modules\Admin\Http\Requests\Api\Admin\UpdateAccountRequest;
use App\Modules\Admin\Http\Requests\Api\Admin\ChangePasswordRequest;
use App\Modules\Admin\Application\UseCases\Admin\AuthAdminUseCase;

class AuthAdminController extends Controller
{
    protected $adminUseCase;

    public function __construct(AuthAdminUseCase $adminUseCase)
    {
        $this->adminUseCase = $adminUseCase;
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/logout",
     *     summary="Log out the authenticated Admin",
     *     tags={"Authenticated Admin"},
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
     *         description="Admin logged out successfully",
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
        return $this->adminUseCase->logout($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/change_password",
     *     summary="Change the authenticated Admin's password",
     *     tags={"Authenticated Admin"},
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
        return $this->adminUseCase->changePassword($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/update_account",
     *     summary="Update the authenticated Admin's account details",
     *     tags={"Authenticated Admin"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", nullable=true, description="Admin's full name", example="Jane Doe")
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
        return $this->adminUseCase->updateAccount($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/Admin/delete_account",
     *     summary="Delete the authenticated Admin's account",
     *     tags={"Authenticated Admin"},
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
        return $this->adminUseCase->deleteAccount()->response();
    }
}
