<?php

namespace App\Modules\Diploma\Http\Controllers\Dashboard\Faq;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Diploma\Application\UseCases\Faq\FaqUseCase;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FaqIdRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\CreateFaqRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FetchFaqRequest;
use App\Modules\Diploma\Http\Requests\Dashboard\Faq\UpdateFaqRequest;

// use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FaqIdRequest;
// use App\Modules\Diploma\Http\Requests\Dashboard\Faq\FaqIdRequest;




class FaqController extends Controller
{
    protected $faqUseCase;

    public function __construct(FaqUseCase $faqUseCase)
    {
        $this->faqUseCase = $faqUseCase;
    }

    /**
     *  @OA\Info(
     *     title="Faq API",
     *     version="1.1",
     *     description="API documentation for managing Faqs , video and lessons."
     * )
     * @OA\Post(
     *     path="/dashboard/fetch_faqs",
     *     summary="Fetch a list of faqs",
     *     tags={"Dashboard Faq"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", nullable=true, description="Filter by faq title", example="Math 101"),
     *             @OA\Property(property="type", type="string", nullable=true, description="Filter by faq type", example="online"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Filter by parent faq ID", example=1),
     *             @OA\Property(property="code", type="string", nullable=true, description="Filter by faq code", example="MATH101")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of faqs",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    
    public function fetchFaqs(FetchFaqRequest $request)
    {
        return $this->faqUseCase->fetchFaqs($request->toDTO())->response();
    }
    /**
     * @OA\Post(
     *     path="/dashboard/fetch_faq_details",
     *     summary="Fetch details of a specific faq",
     *     tags={"Dashboard Faq"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="faq_id", type="integer", description="The ID of the faq", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Faq details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Faq not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid faq ID"
     *     )
     * )
     */
    public function fetchFaqDetails(FaqIdRequest $request)
    {
        return $this->faqUseCase->fetchFaqDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_faq",
     *     summary="Create a new faq",
     *     tags={"Dashboard Faq"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", description="Faq translations", @OA\Items(type="object")),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent faq ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="stage_id", type="integer", nullable=true, description="Stage ID", example=1),
     *             @OA\Property(property="subject_id", type="integer", nullable=true, description="Subject ID", example=1),
     *             @OA\Property(property="type", type="string", description="Faq type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Faq status", example="active"),
     *             @OA\Property(property="is_private", type="boolean", nullable=true, description="Is the faq private?", example=true),
     *             @OA\Property(property="has_website", type="boolean", nullable=true, description="Does the faq have a website?", example=false),
     *             @OA\Property(property="has_app", type="boolean", nullable=true, description="Does the faq have an app?", example=false),
     *             @OA\Property(property="start_date", type="string", format="date", description="Faq start date", example="2025-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", description="Faq end date", example="2025-06-30"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Faq image URL or file", example="faq.jpg"),
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
     *         description="Faq created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function createFaq(CreateFaqRequest $request)
    {
        return $this->faqUseCase->createFaq($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_faq",
     *     summary="Update an existing faq",
     *     tags={"Dashboard Faq"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="faq_id", type="integer", description="The ID of the faq to update", example=1),
     *             @OA\Property(property="translations", type="array", nullable=true, description="Faq translations", @OA\Items(type="object")),
     *             @OA\Property(property="code", type="string", nullable=true, maxLength=255, description="Faq code", example="MATH101"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent faq ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Faq type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Faq status", example="active"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Faq image URL or file", example="faq.jpg"),
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
     *         description="Faq updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Faq not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function updateFaq(UpdateFaqRequest $request)
    {
        return $this->faqUseCase->updateFaq($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_faq",
     *     summary="Delete a faq",
     *     tags={"Dashboard Faq"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="faq_id", type="integer", description="The ID of the faq to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Faq deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Faq not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid faq ID"
     *     )
     * )
     */
    public function deleteFaq(FaqIdRequest $request)
    {
        return $this->faqUseCase->deleteFaq($request->toDTO())->response();
    }
}
