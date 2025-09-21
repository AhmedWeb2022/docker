<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Lesson;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Lesson\LessonUseCase;
use App\Modules\Course\Http\Requests\Dashboard\Lesson\FetchLessonRequest;
use App\Modules\Course\Http\Requests\Global\Lesson\LessonIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Lesson\CreateLessonRequest;
use App\Modules\Course\Http\Requests\Dashboard\Lesson\UpdateLessonRequest;

class LessonController extends Controller
{
    protected $lessonUseCase;

    public function __construct(LessonUseCase $lessonUseCase)
    {
        $this->lessonUseCase = $lessonUseCase;
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_lessons",
     *     summary="Fetch a list of lessons",
     *     tags={"Dashboard Lesson"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", nullable=true, description="Filter by lesson translations", @OA\Items(type="object")),
     *             @OA\Property(property="course_id", type="integer", nullable=true, description="Filter by course ID", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Filter by parent course ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Filter by organization ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Filter by lesson type", example="video"),
     *             @OA\Property(property="status", type="string", nullable=true, description="Filter by lesson status", example="active"),
     *             @OA\Property(property="is_free", type="boolean", nullable=true, description="Filter by free status", example=true),
     *             @OA\Property(property="is_standalone", type="boolean", nullable=true, description="Filter by standalone status", example=false),
     *             @OA\Property(property="price", type="number", nullable=true, description="Filter by price", example=29.99),
     *             @OA\Property(property="image", type="string", nullable=true, description="Filter by image", example="lesson.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of lessons",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchLessons(FetchLessonRequest $request)
    {
        return $this->lessonUseCase->fetchLessons($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_lesson_details",
     *     summary="Fetch details of a specific lesson",
     *     tags={"Dashboard Lesson"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="lesson_id", type="integer", description="The ID of the lesson", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lesson details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lesson not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid lesson ID"
     *     )
     * )
     */
    public function fetchLessonDetails(LessonIdRequest $request)
    {
        return $this->lessonUseCase->fetchLessonDetails($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_lesson",
     *     summary="Create a new lesson",
     *     tags={"Dashboard Lesson"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", description="Lesson translations", @OA\Items(type="object")),
     *             @OA\Property(property="course_id", type="integer", description="The ID of the associated course", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent course ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="type", type="string", description="Lesson type", example="video"),
     *             @OA\Property(property="status", type="string", nullable=true, description="Lesson status", example="active"),
     *             @OA\Property(property="is_free", type="boolean", nullable=true, description="Is the lesson free?", example=true),
     *             @OA\Property(property="is_standalone", type="boolean", nullable=true, description="Is the lesson standalone?", example=false),
     *             @OA\Property(property="price", type="number", nullable=true, description="Lesson price", example=29.99),
     *             @OA\Property(property="image", type="string", nullable=true, description="Lesson image URL or file", example="lesson.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lesson created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function createLesson(CreateLessonRequest $request)
    {
        return $this->lessonUseCase->createLesson($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_lesson",
     *     summary="Update an existing lesson",
     *     tags={"Dashboard Lesson"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="lesson_id", type="integer", description="The ID of the lesson to update", example=1),
     *             @OA\Property(property="translations", type="array", description="Lesson translations", @OA\Items(type="object")),
     *             @OA\Property(property="course_id", type="integer", description="The ID of the associated course", example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent course ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Lesson type", example="video"),
     *             @OA\Property(property="status", type="string", nullable=true, description="Lesson status", example="active"),
     *             @OA\Property(property="is_free", type="boolean", nullable=true, description="Is the lesson free?", example=true),
     *             @OA\Property(property="is_standalone", type="boolean", nullable=true, description="Is the lesson standalone?", example=false),
     *             @OA\Property(property="price", type="number", nullable=true, description="Lesson price", example=29.99),
     *             @OA\Property(property="image", type="string", nullable=true, description="Lesson image URL or file", example="lesson.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lesson updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lesson not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function updateLesson(UpdateLessonRequest $request)
    {
        return $this->lessonUseCase->updateLesson($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_lesson",
     *     summary="Delete a lesson",
     *     tags={"Dashboard Lesson"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="lesson_id", type="integer", description="The ID of the lesson to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Lesson deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lesson not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid lesson ID"
     *     )
     * )
     */
    public function deleteLesson(LessonIdRequest $request)
    {
        return $this->lessonUseCase->deleteLesson($request->toDTO())->response();
    }
}
