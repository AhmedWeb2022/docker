<?php

namespace App\Modules\Admin\Http\Controllers\Dashboard\Admin;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Admin\Application\UseCases\Admin\AdminUseCase;
use App\Modules\Admin\Http\Requests\Global\Admin\AdminIdRequest;
use App\Modules\Admin\Http\Requests\Dashboard\Admin\FetchAdminRequest;
use App\Modules\Admin\Http\Requests\Dashboard\Admin\CreateAdminRequest;
use App\Modules\Admin\Http\Requests\Dashboard\Admin\UpdateAdminRequest;
use App\Modules\Admin\Http\Requests\Dashboard\Admin\FetchAdminDetailsRequest;

class AdminController extends Controller
{
    protected $adminUseCase;

    public function __construct(AdminUseCase $adminUseCase)
    {
        $this->adminUseCase = $adminUseCase;
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_Admins",
     *     summary="Fetch a list of Admins",
     *     tags={"Dashboard Admin"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", nullable=true, description="Filter by Admin name", example="Jane"),
     *             @OA\Property(property="email", type="string", format="email", nullable=true, description="Filter by Admin email", example="jane@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of Admins",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchAdmins(FetchAdminRequest $request)
    {
        return $this->adminUseCase->fetchAdmins($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_Admin_details",
     *     summary="Fetch details of a specific Admin",
     *     tags={"Dashboard Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Admin_id", type="integer", description="The ID of the Admin", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Admin ID"
     *     )
     * )
     */
    public function fetchAdminDetails(AdminIdRequest $request)
    {
        return $this->adminUseCase->fetchAdminDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_Admin",
     *     summary="Create a new Admin",
     *     tags={"Dashboard Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="Admin's full name", example="Jane Doe"),
     *             @OA\Property(property="username", type="string", nullable=true, description="Admin's username", example="janedoe"),
     *             @OA\Property(property="phone", type="string", description="Admin's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", description="Admin's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", description="Admin's email address", example="jane@example.com"),
     *             @OA\Property(property="password", type="string", description="Admin's password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Admin created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or duplicate data (e.g., email/phone already exists)"
     *     )
     * )
     */
    public function createAdmin(CreateAdminRequest $request)
    {
        return $this->adminUseCase->createAdmin($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_Admin",
     *     summary="Update an existing Admin",
     *     tags={"Dashboard Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Admin_id", type="integer", description="The ID of the Admin to update", example=1),
     *             @OA\Property(property="name", type="string", nullable=true, description="Admin's full name", example="Jane Doe"),
     *             @OA\Property(property="username", type="string", nullable=true, description="Admin's username", example="janedoe"),
     *             @OA\Property(property="phone", type="string", nullable=true, description="Admin's phone number", example="1234567890"),
     *             @OA\Property(property="identify_number", type="string", nullable=true, description="Admin's identification number", example="ID12345"),
     *             @OA\Property(property="email", type="string", format="email", nullable=true, description="Admin's email address", example="jane@example.com"),
     *             @OA\Property(property="password", type="string", nullable=true, description="Admin's new password", example="newsecret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admin updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or duplicate data (e.g., email/phone already exists)"
     *     )
     * )
     */
    public function updateAdmin(UpdateAdminRequest $request)
    {
        return $this->adminUseCase->updateAdmin($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_Admin",
     *     summary="Delete a Admin",
     *     tags={"Dashboard Admin"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Admin_id", type="integer", description="The ID of the Admin to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Admin deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admin not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Admin ID"
     *     )
     * )
     */
    public function deleteAdmin(AdminIdRequest $request)
    {
        return $this->adminUseCase->deleteAdmin($request->toDTO())->response();
    }
}
