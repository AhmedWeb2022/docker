<?php

namespace App\Modules\Employee\Http\Controllers\Api\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\User\Http\Resources\User\UserResource;
use App\Modules\User\Application\DTOS\User\UserFilterDTO;
use App\Modules\User\Application\UseCases\User\UserUseCase;
use App\Modules\Employee\Http\Requests\Api\Employee\LogoutRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\EmployeeRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\UpdateAccountRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\ChangePasswordRequest;
use App\Modules\Employee\Application\UseCases\Employee\AuthEmployeeUseCase;
use App\Modules\Employee\Http\Requests\Api\Employee\CheckAuthenticationRequest;
use App\Modules\Employee\Http\Requests\Api\Employee\CheckTeacherAuthenticationRequest;

class AuthEmployeeController extends Controller
{
    protected $employeeUseCase;
    protected $userUseCase;

    public function __construct(AuthEmployeeUseCase $employeeUseCase)
    {
        $this->employeeUseCase = $employeeUseCase;
        $this->userUseCase = new UserUseCase();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/logout",
     *     summary="Log out the authenticated employee",
     *     tags={"Authenticated Employee"},
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
     *         description="Employee logged out successfully",
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
        return $this->employeeUseCase->logout($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/change_password",
     *     summary="Change the authenticated employee's password",
     *     tags={"Authenticated Employee"},
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
        return $this->employeeUseCase->changePassword($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/update_account",
     *     summary="Update the authenticated employee's account details",
     *     tags={"Authenticated Employee"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", nullable=true, description="Employee's full name", example="John Smith")
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
        return $this->employeeUseCase->updateAccount($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/api/employee/delete_account",
     *     summary="Delete the authenticated employee's account",
     *     tags={"Authenticated Employee"},
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
        return $this->employeeUseCase->deleteAccount()->response();
    }

    public function checkAuthentication(CheckAuthenticationRequest $request)
    {
        return $this->employeeUseCase->checkAuthentication($request->toDTO())->response();
    }

    public function checkTeacherAuthentication(CheckTeacherAuthenticationRequest $request)
    {
        return $this->employeeUseCase->checkTeacherAuthentication($request->toDTO())->response();
    }

    public function fetchTeacherStudents(EmployeeRequest $request)
    {
        $studentIds = $this->employeeUseCase->fetchTeacherStudents($request->toDTO())->getData();
        // dd($studentIds); // Debugging line, can be removed later
        $userDTO = UserFilterDTO::fromArray([
            'id' => $studentIds
        ]);

        // $userDTO->id = $studentIds;
        // dd($userDTO); // Debugging line, can be removed later
        return $this->userUseCase->fetchUsers($userDTO)->response();
    }
}
