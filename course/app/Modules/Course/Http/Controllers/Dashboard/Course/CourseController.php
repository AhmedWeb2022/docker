<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Course;

use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Modules\Course\Application\UseCases\Course\CourseUseCase;
use App\Modules\Course\Http\Requests\Global\Course\CourseIdRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\AddLevelsRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\FetchCourseRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\CreateCourseRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\UpdateCourseRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\AddCourseOfferRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\FetchCourseLevelRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\FetchCourseOfferRequest;
use App\Modules\Course\Http\Requests\Dashboard\Course\DeleteCourseOfferRequest;
use App\Modules\Course\Application\DTOS\CourseSubjectStage\CourseSubjectStageDTO;
use App\Modules\Course\Application\UseCases\CourseSubjectStage\CourseSubjectStageUseCase;

class CourseController extends Controller
{
    protected $courseUseCase;
    protected $courseSubjectStageUseCase;

    public function __construct(CourseUseCase $courseUseCase)
    {
        $this->courseUseCase = $courseUseCase;
        $this->courseSubjectStageUseCase = new CourseSubjectStageUseCase();
    }

    /**
     *  @OA\Info(
     *     title="Course API",
     *     version="1.1",
     *     description="API documentation for managing Courses , video and lessons."
     * )
     * @OA\Post(
     *     path="/dashboard/fetch_courses",
     *     summary="Fetch a list of courses",
     *     tags={"Dashboard Course"},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", nullable=true, description="Filter by course title", example="Math 101"),
     *             @OA\Property(property="type", type="string", nullable=true, description="Filter by course type", example="online"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Filter by parent course ID", example=1),
     *             @OA\Property(property="code", type="string", nullable=true, description="Filter by course code", example="MATH101")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of courses",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function fetchCourses(FetchCourseRequest $request)
    {
        return $this->courseUseCase->fetchCourses($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/fetch_course_details",
     *     summary="Fetch details of a specific course",
     *     tags={"Dashboard Course"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="course_id", type="integer", description="The ID of the course", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid course ID"
     *     )
     * )
     */
    public function fetchCourseDetails(CourseIdRequest $request)
    {
        return $this->courseUseCase->fetchCourseDetails($request->toDTO())->response();
    }

    public function fetchCourseDetail(CourseIdRequest $request)
    {
        return $this->courseUseCase->fetchCourseDetail($request->toDTO())->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/create_course",
     *     summary="Create a new course",
     *     tags={"Dashboard Course"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="translations", type="array", description="Course translations", @OA\Items(type="object")),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent course ID", example=1),
     *             @OA\Property(property="organization_id", type="integer", nullable=true, description="Organization ID", example=1),
     *             @OA\Property(property="stage_id", type="integer", nullable=true, description="Stage ID", example=1),
     *             @OA\Property(property="subject_id", type="integer", nullable=true, description="Subject ID", example=1),
     *             @OA\Property(property="type", type="string", description="Course type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Course status", example="active"),
     *             @OA\Property(property="is_private", type="boolean", nullable=true, description="Is the course private?", example=true),
     *             @OA\Property(property="has_website", type="boolean", nullable=true, description="Does the course have a website?", example=false),
     *             @OA\Property(property="has_app", type="boolean", nullable=true, description="Does the course have an app?", example=false),
     *             @OA\Property(property="start_date", type="string", format="date", description="Course start date", example="2025-01-01"),
     *             @OA\Property(property="end_date", type="string", format="date", description="Course end date", example="2025-06-30"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Course image URL or file", example="course.jpg"),
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
     *         description="Course created successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function createCourse(CreateCourseRequest $request)
    {
        $courseDTO = $request->toDTO();
        /** @var Course $course */
        $course =  $this->courseUseCase->createCourse($courseDTO)->getData();
        $courseDTO->course_id = $course->id;
        if (isset($courseDTO->subject_stage_ids) && is_array($courseDTO->subject_stage_ids) && count($courseDTO->subject_stage_ids) > 0) {
            /** @var CourseSubjectStageDTO $courseSubjectStageDTO */
            $courseSubjectStageDTO = CourseSubjectStageDTO::fromArray([
                'course_id' => $course->id,
            ]);
            foreach ($courseDTO->subject_stage_ids as $subject_stage_id) {
                $courseSubjectStageDTO->subject_stage_id = $subject_stage_id;
                $this->courseSubjectStageUseCase->createCourseSubjectStage($courseSubjectStageDTO);
            }
        }

        return $this->courseUseCase->fetchCourseDetails($courseDTO)->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/update_course",
     *     summary="Update an existing course",
     *     tags={"Dashboard Course"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="course_id", type="integer", description="The ID of the course to update", example=1),
     *             @OA\Property(property="translations", type="array", nullable=true, description="Course translations", @OA\Items(type="object")),
     *             @OA\Property(property="code", type="string", nullable=true, maxLength=255, description="Course code", example="MATH101"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Parent course ID", example=1),
     *             @OA\Property(property="type", type="string", nullable=true, description="Course type", example="online"),
     *             @OA\Property(property="status", type="string", nullable=true, enum={"active", "inactive"}, description="Course status", example="active"),
     *             @OA\Property(property="image", type="string", nullable=true, description="Course image URL or file", example="course.jpg"),
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
     *         description="Course updated successfully",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or validation error"
     *     )
     * )
     */
    public function updateCourse(UpdateCourseRequest $request)
    {
        $courseDTO = $request->toDTO();
        /** @var Course $course */
        $course = $this->courseUseCase->updateCourse($courseDTO)->getData();
        if (isset($courseDTO->subject_stage_ids) && is_array($courseDTO->subject_stage_ids) && count($courseDTO->subject_stage_ids) > 0) {
            /** @var CourseSubjectStageDTO $courseSubjectStageDTO */
            $courseSubjectStageDTO = CourseSubjectStageDTO::fromArray([
                'course_id' => $course->id,
            ]);
            $this->courseSubjectStageUseCase->deleteAllCourseSubjectStageByCourse($courseSubjectStageDTO);
            foreach ($courseDTO->subject_stage_ids as $subject_stage_id) {
                $courseSubjectStageDTO->subject_stage_id = $subject_stage_id;
                $this->courseSubjectStageUseCase->createCourseSubjectStage($courseSubjectStageDTO);
            }
        }

        return $this->courseUseCase->fetchCourseDetails($courseDTO)->response();
    }

    /**
     * @OA\Post(
     *     path="/dashboard/delete_course",
     *     summary="Delete a course",
     *     tags={"Dashboard Course"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="course_id", type="integer", description="The ID of the course to delete", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Course deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Course not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid course ID"
     *     )
     * )
     */
    public function deleteCourse(CourseIdRequest $request)
    {
        return $this->courseUseCase->deleteCourse($request->toDTO())->response();
    }


    public function addLevels(AddLevelsRequest $request)
    {
        return $this->courseUseCase->addLevels($request->toDTO())->response();
    }

    public function fetchLevelCourses(FetchCourseLevelRequest $request)
    {
        return $this->courseUseCase->fetchLevelCourses($request->toDTO())->response();
    }

    public function addCourseOffer(AddCourseOfferRequest $request)
    {
        return $this->courseUseCase->addCourseOffer($request->toDTO())->response();
    }

    public function fetchCourseOffers(FetchCourseOfferRequest $request)
    {
        return $this->courseUseCase->fetchCourseOffers($request->toDTO())->response();
    }

    public function deleteCourseOffer(DeleteCourseOfferRequest $request){
        return $this->courseUseCase->deleteCourseOffer($request->toDTO())->response();
    }

    public function toggleFavoriteCourse(CourseIdRequest $request){
        return $this->courseUseCase->toggleFavoriteCourse($request->toDTO())->response();
    }
    public function toggleHiddenCourse(CourseIdRequest $request){
        return $this->courseUseCase->toggleHiddenCourse($request->toDTO())->response();
    }
}
