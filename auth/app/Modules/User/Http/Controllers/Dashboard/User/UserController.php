<?php

namespace App\Modules\User\Http\Controllers\Dashboard\User;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\User\Application\UseCases\User\UserUseCase;
use App\Modules\User\Http\Requests\Global\User\UserIdRequest;
use App\Modules\User\Http\Requests\Dashboard\User\FetchUserRequest;
use App\Modules\User\Http\Requests\Dashboard\User\CreateUserRequest;
use App\Modules\User\Http\Requests\Dashboard\User\UpdateUserRequest;
use App\Modules\User\Http\Requests\Dashboard\User\FetchUserDetailsRequest;

class UserController extends Controller
{
    protected $userUseCase;

    public function __construct(UserUseCase $userUseCase)
    {
        $this->userUseCase = $userUseCase;
    }

    /**
     * @OA\Info(
     *     title="User API",
     *     version="1.1",
     *     description="API documentation for managing stages with localization support."
     * )
     *
     * @OA\Post(
     *     path="/dashboard/fetch_users",
     *     summary="Fetch a list of users",
     *     tags={"User Dashboard"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", nullable=true, description="Filter by user name"),
     *             @OA\Property(property="email", type="string", format="email", nullable=true, description="Filter by user email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of users",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchUsers(FetchUserRequest $request)
    {
        return $this->userUseCase->fetchUsers($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_user_details",
     *     summary="Fetch details of a specific user",
     *     tags={"User Dashboard"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", description="The ID of the user", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid user ID"
     *     )
     * )
     */
    public function fetchUserDetails(UserIdRequest $request)
    {
        return $this->userUseCase->fetchUserDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_user",
     *     summary="Create a new user",
     *     tags={"User Dashboard"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="User's full name"),
     *             @OA\Property(property="username", type="string", nullable=true, description="User's username"),
     *             @OA\Property(property="phone", type="string", description="User's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", description="User's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email address", example="user@example.com"),
     *             @OA\Property(property="password", type="string", description="User's password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or duplicate data (e.g., email/phone already exists)"
     *     )
     * )
     */
    public function createUser(CreateUserRequest $request)
    {
        return $this->userUseCase->createUser($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_user",
     *     summary="Update an existing user",
     *     tags={"User Dashboard"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", description="The ID of the user to update", example=1),
     *             @OA\Property(property="name", type="string", nullable=true, description="User's full name"),
     *             @OA\Property(property="username", type="string", nullable=true, description="User's username"),
     *             @OA\Property(property="phone", type="string", nullable=true, description="User's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", nullable=true, description="User's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", nullable=true, description="User's email address", example="user@example.com"),
     *             @OA\Property(property="password", type="string", nullable=true, description="User's new password", example="newsecret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or duplicate data (e.g., email/phone already exists)"
     *     )
     * )
     */
    public function updateUser(UpdateUserRequest $request)
    {
        return $this->userUseCase->updateUser($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_user",
     *     summary="Delete a user",
     *     tags={"User Dashboard"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", description="The ID of the user to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid user ID"
     *     )
     * )
     */
    public function deleteUser(UserIdRequest $request)
    {
        return $this->userUseCase->deleteUser($request->toDTO())->response();
    }

    public function forceLogout(UserIdRequest $request)
    {
        return $this->userUseCase->forceLogout($request->toDTO())->response();
    }

    public function blockUser(UserIdRequest $request)
    {
        return $this->userUseCase->blockUser($request->toDTO())->response();
    }

    public function unblockUser(UserIdRequest $request)
    {
        return $this->userUseCase->unblockUser($request->toDTO())->response();
    }
}
