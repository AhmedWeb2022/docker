<?php

namespace App\Modules\Diploma\Http\Controllers\Dashboard\Diploma;

use App\Modules\Diploma\Http\Requests\Dashboard\Diploma\FetchDiplomaRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Diploma\UpdateDiplomaRequest;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Diploma\Application\UseCases\Diploma\DiplomaUseCase;
use App\Modules\Diploma\Http\Requests\Global\Diploma\DiplomaIdRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Diploma\AddLevelsRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Diploma\CreateDiplomaRequest;

class DiplomaController extends Controller
{
    protected $DiplomaUseCase;

    public function __construct(DiplomaUseCase $DiplomaUseCase)
    {
        $this->DiplomaUseCase = $DiplomaUseCase;
    }

    /**
     *  @OA\Info(
     *     title="Diploma API",
     *     version="1.1",
     *     description="API documentation for managing Diplomas , video and lessons."
     * )
     * @OA\Post(
     *     path="/dashboard/fetch_Diplomas",
     *     summary="Fetch a list of Diplomas",
     *     tags={"Dashboard Diploma"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", nullable=true, description="Filter by Diploma title", example="Math 101"),
     *             @OA\Property(property="type", type="string", nullable=true, description="Filter by Diploma type", example="online"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Filter by parent Diploma ID", example=1),
     *             @OA\Property(property="code", type="string", nullable=true, description="Filter by Diploma code", example="MATH101")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of Diplomas",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchDiplomas(FetchDiplomaRequest $request)
    {
        return $this->DiplomaUseCase->fetchDiplomas($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_Diploma_details",
     *     summary="Fetch details of a specific Diploma",
     *     tags={"Dashboard Diploma"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Diploma_id", type="integer", description="The ID of the Diploma", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Diploma details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Diploma not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Diploma ID"
     *     )
     * )
     */
    public function fetchDiplomaDetails(DiplomaIdRequest $request)
    {
        return $this->DiplomaUseCase->fetchDiplomaDetails($request->toDTO())->response();
    }

    public function fetchDiplomaDetail(DiplomaIdRequest $request)
    {
        return $this->DiplomaUseCase->fetchDiplomaDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_Diploma",
     *     summary="Create a new Diploma",
     *     tags={"Dashboard Diploma"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", description="Diploma translations", @OA\Items(type="object")),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent Diploma ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="stage_id", type="integer", nullable=true, description="Stage ID", example=1),
     *             @OA\Property(property="subject_id", type="integer", nullable=true, description="Subject ID", example=1),
     *             @OA\Property(property="type", type="string", description="Diploma type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Diploma status", example="active"),
     *             @OA\Property(property="is_private", type="boolean", nullable=true, description="Is the Diploma private?", example=true),
     *             @OA\Property(property="has_website", type="boolean", nullable=true, description="Does the Diploma have a website?", example=false),
     *             @OA\Property(property="has_app", type="boolean", nullable=true, description="Does the Diploma have an app?", example=false),
     *             @OA\Property(property="start_date", type="string", format="date", description="Diploma start date", example="2025-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", description="Diploma end date", example="2025-06-30"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Diploma image URL or file", example="Diploma.jpg"),
     *             @OA\Property(
     *                 property="video",
     *                 type="object",
     *                 nullable=true,
     *                 description="Video details",
     *                 @OA\Property(property="is_file", type="integer", enum={0, 1}, nullable=true, description="Is the video a file? (1 = yes, 0 = no)", example=1),
     *                 @OA\Property(property="video_type", type="string", description="Type of video", example="mp4"),
     *                 @OA\Property(property="file", type="string", description="Video file (required if is_file = 1)", example="video.mp4"),
     *                 @OA\Property(property="link", type="string", description="Video link (required if is_file = 0)", example="https://example.com/video")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Diploma created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function createDiploma(CreateDiplomaRequest $request)
    {
        return $this->DiplomaUseCase->createDiploma($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_Diploma",
     *     summary="Update an existing Diploma",
     *     tags={"Dashboard Diploma"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Diploma_id", type="integer", description="The ID of the Diploma to update", example=1),
     *             @OA\Property(property="translations", type="array", nullable=true, description="Diploma translations", @OA\Items(type="object")),
     *             @OA\Property(property="code", type="string", nullable=true, maxLength=255, description="Diploma code", example="MATH101"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent Diploma ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Diploma type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Diploma status", example="active"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Diploma image URL or file", example="Diploma.jpg"),
     *             @OA\Property(
     *                 property="video",
     *                 type="object",
     *                 nullable=true,
     *                 description="Video details",
     *                 @OA\Property(property="is_file", type="integer", enum={0, 1}, nullable=true, description="Is the video a file? (1 = yes, 0 = no)", example=1),
     *                 @OA\Property(property="video_type", type="string", description="Type of video", example="mp4"),
     *                 @OA\Property(property="file", type="string", description="Video file (required if is_file = 1)", example="video.mp4"),
     *                 @OA\Property(property="link", type="string", description="Video link (required if is_file = 0)", example="https://example.com/video")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Diploma updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Diploma not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function updateDiploma(UpdateDiplomaRequest $request)
    {
        return $this->DiplomaUseCase->updateDiploma($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_Diploma",
     *     summary="Delete a Diploma",
     *     tags={"Dashboard Diploma"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Diploma_id", type="integer", description="The ID of the Diploma to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Diploma deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Diploma not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Diploma ID"
     *     )
     * )
     */
    public function deleteDiploma(DiplomaIdRequest $request)
    {
        return $this->DiplomaUseCase->deleteDiploma($request->toDTO())->response();
    }



}
