<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Content;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Application\DTOS\Live\LiveDTO;
use App\Modules\Course\Application\DTOS\Poll\PollDTO;
use App\Modules\Course\Application\DTOS\Audio\AudioDTO;
use App\Modules\Course\Application\DTOS\Session\SessionDTO;
use App\Modules\Course\Application\DTOS\Document\DocumentDTO;
use App\Modules\Course\Application\UseCases\Live\LiveUseCase;
use App\Modules\Course\Application\UseCases\Poll\PollUseCase;
use App\Modules\Course\Application\DTOS\Referance\ReferanceDTO;
use App\Modules\Course\Application\UseCases\Audio\AudioUseCase;
use App\Modules\Course\Application\DTOS\LiveAnswer\LiveAnswerDTO;
use App\Modules\Course\Application\DTOS\PollAnswer\PollAnswerDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Application\UseCases\Content\ContentUseCase;
use App\Modules\Course\Application\UseCases\Session\SessionUseCase;
use App\Modules\Course\Application\DTOS\LiveQuestion\LiveQuestionDTO;
use App\Modules\Course\Application\UseCases\Document\DocumentUseCase;
use App\Modules\Course\Http\Requests\Global\Content\ContentIdRequest;
use App\Modules\Course\Application\Trait\Content\HandlesContentUpdate;

use App\Modules\Course\Application\UseCases\Referance\ReferanceUseCase;
use App\Modules\Course\Application\Trait\Content\HandlesContentCreation;
use App\Modules\Course\Application\UseCases\LiveAnswer\LiveAnswerUseCase;
use App\Modules\Course\Application\UseCases\PollAnswer\PollAnswerUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Content\FetchContentRequest;
use App\Modules\Course\Http\Requests\Dashboard\Content\CreateContentRequest;
use App\Modules\Course\Http\Requests\Dashboard\Content\UpdateContentRequest;
use App\Modules\Course\Application\UseCases\LiveQuestion\LiveQuestionUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Content\UpdateContentOrderRequest;
use App\Modules\Course\Application\DTOS\LiveAnswerAttachment\LiveAnswerAttachmentDTO;
use App\Modules\Course\Application\DTOS\LiveQuestionAttachment\LiveQuestionAttachmentDTO;
use App\Modules\Course\Application\UseCases\LiveAnswerAttachment\LiveAnswerAttachmentUseCase;
use App\Modules\Course\Application\UseCases\LiveQuestionAttachment\LiveQuestionAttachmentUseCase;

class ContentController extends Controller
{
    use HandlesContentCreation;
    use HandlesContentUpdate;

    protected $contentUseCase;
    protected $sessionUseCase;
    protected $audioUseCase;
    protected $documentUseCase;
    protected $pollUseCase;
    protected $pollAnswerUseCase;
    protected $referanceUseCase;
    protected $liveUseCase;
    protected $liveQuestionUseCase;
    protected $liveAnswerUseCase;
    protected $liveAnswerAttachmentUseCase;
    protected $liveQuestionAttachmentUseCase;
    protected $employee;
    public function __construct(
        ContentUseCase $contentUseCase,
        SessionUseCase $sessionUseCase,
        AudioUseCase $audioUseCase,
        DocumentUseCase $documentUseCase,
        PollUseCase $pollUseCase,
        PollAnswerUseCase $pollAnswerUseCase,
        ReferanceUseCase $referanceUseCase,
        LiveUseCase $liveUseCase,
        LiveQuestionUseCase $liveQuestionUseCase,
        LiveAnswerUseCase $liveAnswerUseCase,
        LiveAnswerAttachmentUseCase $liveAnswerAttachmentUseCase,
        LiveQuestionAttachmentUseCase $liveQuestionAttachmentUseCase,
    ) {
        $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);

        $this->contentUseCase = $contentUseCase;
        $this->sessionUseCase = $sessionUseCase;
        $this->audioUseCase = $audioUseCase;
        $this->documentUseCase = $documentUseCase;
        $this->pollUseCase = $pollUseCase;
        $this->pollAnswerUseCase = $pollAnswerUseCase;
        $this->referanceUseCase = $referanceUseCase;
        $this->liveUseCase = $liveUseCase;
        $this->liveQuestionUseCase = $liveQuestionUseCase;
        $this->liveAnswerUseCase = $liveAnswerUseCase;
        $this->liveAnswerAttachmentUseCase = $liveAnswerAttachmentUseCase;
        $this->liveQuestionAttachmentUseCase = $liveQuestionAttachmentUseCase;
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_Contents",
     *     summary="Fetch a list of Contents",
     *     tags={"Dashboard Content"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", nullable=true, description="Filter by Content translations", @OA\Items(type="object")),
     *             @OA\Property(property="course_id", type="integer", nullable=true, description="Filter by course ID", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Filter by parent course ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Filter by organization ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Filter by Content type", example="video"),
     *             @OA\Property(property="status", type="string", nullable=true, description="Filter by Content status", example="active"),
     *             @OA\Property(property="is_free", type="boolean", nullable=true, description="Filter by free status", example=true),
     *             @OA\Property(property="is_standalone", type="boolean", nullable=true, description="Filter by standalone status", example=false),
     *             @OA\Property(property="price", type="number", nullable=true, description="Filter by price", example=29.99),
     *             @OA\Property(property="image", type="string", nullable=true, description="Filter by image", example="Content.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of Contents",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchContents(FetchContentRequest $request)
    {
        return $this->contentUseCase->fetchContents($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_Content_details",
     *     summary="Fetch details of a specific Content",
     *     tags={"Dashboard Content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Content_id", type="integer", description="The ID of the Content", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Content not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Content ID"
     *     )
     * )
     */
    public function fetchContentDetails(ContentIdRequest $request)
    {
        return $this->contentUseCase->fetchContentDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_Content",
     *     summary="Create a new Content",
     *     tags={"Dashboard Content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", description="Content translations", @OA\Items(type="object")),
     *             @OA\Property(property="course_id", type="integer", description="The ID of the associated course", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent course ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="type", type="string", description="Content type", example="video"),
     *             @OA\Property(property="status", type="string", nullable=true, description="Content status", example="active"),
     *             @OA\Property(property="is_free", type="boolean", nullable=true, description="Is the Content free?", example=true),
     *             @OA\Property(property="is_standalone", type="boolean", nullable=true, description="Is the Content standalone?", example=false),
     *             @OA\Property(property="price", type="number", nullable=true, description="Content price", example=29.99),
     *             @OA\Property(property="image", type="string", nullable=true, description="Content image URL or file", example="Content.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Content created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function createContent(CreateContentRequest $request)
    {
        $contentDTO = $request->toDTO();
        $contentDTO->created_by = $this->employee->id; // Ensure created_by is set
        /** @var Content $content */
        $content = $this->contentUseCase->createContent($contentDTO)->getData();
        $this->handleContentCreation($contentDTO, $content);
        $contentDTO->content_id = $content->id; // Ensure content_id is set correctly
        return $this->contentUseCase->fetchContentDetails($contentDTO)->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_Content",
     *     summary="Update an existing Content",
     *     tags={"Dashboard Content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Content_id", type="integer", description="The ID of the Content to update", example=1),
     *             @OA\Property(property="translations", type="array", description="Content translations", @OA\Items(type="object")),
     *             @OA\Property(property="course_id", type="integer", description="The ID of the associated course", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent course ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Content type", example="video"),
     *             @OA\Property(property="status", type="string", nullable=true, description="Content status", example="active"),
     *             @OA\Property(property="is_free", type="boolean", nullable=true, description="Is the Content free?", example=true),
     *             @OA\Property(property="is_standalone", type="boolean", nullable=true, description="Is the Content standalone?", example=false),
     *             @OA\Property(property="price", type="number", nullable=true, description="Content price", example=29.99),
     *             @OA\Property(property="image", type="string", nullable=true, description="Content image URL or file", example="Content.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Content not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */

    public function updateContent(UpdateContentRequest $request)
    {
        $contentDTO = $request->toDTO();
        $contentDTO->updated_by = $this->employee->id;
        /** @var Content $content */
        $content = $this->contentUseCase->updateContent($contentDTO)->getData();
        // dd($content);
        $this->handleContentUpdate($contentDTO, $content);
        $contentDTO->content_id = $content->id; // Ensure correct ID for fetch
        return $this->contentUseCase->fetchContentDetails($contentDTO)->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_Content_order",
     *     summary="Update an existing Content",
     *     tags={"Dashboard Content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Content_id", type="integer", description="The ID of the Content to update", example=1),
     *             @OA\Property(property="order", type="integer", description="The Order wanted to update for this content", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Content updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Content not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function updateContentOrder(UpdateContentOrderRequest $request)
    {
        return $this->contentUseCase->updateContentOrder($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_Content",
     *     summary="Delete a Content",
     *     tags={"Dashboard Content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="Content_id", type="integer", description="The ID of the Content to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Content deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Content not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid Content ID"
     *     )
     * )
     */
    public function deleteContent(ContentIdRequest $request)
    {
        return $this->contentUseCase->deleteContent($request->toDTO())->response();
    }
}
