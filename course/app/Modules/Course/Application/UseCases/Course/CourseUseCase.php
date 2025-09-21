<?php

namespace App\Modules\Course\Application\UseCases\Course;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Video\VideoDTO;
use App\Modules\Course\Application\DTOS\Course\CourseDTO;
use App\Modules\Course\Application\DTOS\Course\AddLevelDTO;
use App\Modules\Course\Application\Enums\View\ViewTypeEnum;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Application\DTOS\Platform\PlatformDTO;
use App\Modules\Course\Application\DTOS\Course\CourseLevelDTO;
use App\Modules\Course\Application\DTOS\Course\CourseOfferDTO;
use App\Modules\Course\Application\DTOS\Course\CourseFilterDTO;
use App\Modules\Course\Application\DTOS\Course\CourseSettingDTO;
use App\Modules\Course\Http\Resources\Course\FullCourseResource;
use App\Modules\Course\Http\Resources\Course\CourseLevelResource;
use App\Modules\Course\Http\Resources\Course\CourseOfferResource;
use App\Modules\Course\Http\Resources\Course\CourseTitleResource;
use App\Modules\Course\Http\Resources\Course\CourseDetailsResource;

use App\Modules\Course\Http\Resources\Course\CourseWebsiteApiResource;


use App\Modules\Course\Application\DTOS\CourseTeacher\CourseTeacherDTO;
use App\Modules\Course\Application\Enums\Course\CourseFavoriteTypeEnum;
use App\Modules\Course\Http\Resources\Course\FullCourseDetailsResource;
use App\Modules\Course\Http\Resources\Course\Website\ApiCourseResource;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Application\DTOS\CourseDependency\CoursePaymentDTO;
use App\Modules\Course\Application\DTOS\CourseDependency\CoursePlatformDTO;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionTypeEnum;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionStatusEnum;
use App\Modules\Course\Http\Resources\Course\Website\CourseStatisticsResource;
use App\Modules\Course\Http\Resources\Course\Website\WebsiteCourseContentResource;
use App\Modules\Course\Http\Resources\Course\Website\CourseDetailsForLessonResource;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Video\VideoRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseLevelRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseOfferRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CoursePaymentRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseSettingRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Platform\CoursePlatformRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Subscription\SubscriptionRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\CourseTeacher\CourseTeacherRepository;
use App\Modules\Course\Http\Resources\Course\Website\CourseDetailsResource as WebsiteCourseDetailsResource;


class CourseUseCase
{

    protected $courseRepository;
    protected $videoRepository;
    protected $coursePlatformRepository;
    protected $coursePaymentRepository;
    protected $courseTeacherRepository;
    protected $employee;
    protected $courseSettingRepository;
    protected $courseLevelRepository;
    protected $SubscriptionRepository;

    protected  $courseOfferRepository;
    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->videoRepository = new VideoRepository();
        $this->courseSettingRepository = new CourseSettingRepository();
        $this->coursePlatformRepository = new CoursePlatformRepository();
        $this->coursePaymentRepository = new CoursePaymentRepository();
        $this->courseLevelRepository = new CourseLevelRepository();
        $this->courseTeacherRepository = new CourseTeacherRepository();
        $this->courseOfferRepository = new CourseOfferRepository();
        $this->SubscriptionRepository = new SubscriptionRepository();
        // $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
    }

    public function fetchCourses(CourseFilterDTO $courseFilterDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($courseFilterDTO);
            $courses = $this->courseRepository->filter(
                $courseFilterDTO,
                operator: 'like',
                translatableFields: ['title', 'description'],
                paginate: $courseFilterDTO->paginate,
                limit: $courseFilterDTO->limit,
                whereHasRelations: [
                    'courseTeachers' => [
                        'teacher_id' => $courseFilterDTO->teacher_id
                    ]
                ]
            );
            // dd($courses);
            $resource = $this->HandelCourseResource(
                $courses,
                $view,
                $courseFilterDTO->with_lessons,
                $courseFilterDTO->paginate
            );
            // $resource = $view == ViewTypeEnum::WEBSITE->value ? CourseWebsiteApiResource::collection($courses) : ($courseFilterDTO->with_lessons ? FullCourseResource::collection($courses) : CourseResource::collection($courses));
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $courseFilterDTO->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    public function fetchCourseDetails(CourseDTO $courseDTO, $view = ViewTypeEnum::DASHBOARD->value, $onlyContent = false): DataStatus
    {
        try {
            $course = $this->courseRepository->getById($courseDTO->course_id);
            $resource = $this->HandelCourseDetailResource(
                course: $course,
                viewType: $view,
                with_lessons: $courseDTO->with_lessons,
                paginate: false,
                onlyContent: $onlyContent,
                lesson_id: $courseDTO->lesson_id
            );
            return DataSuccess(
                status: true,
                message: 'Course fetched successfully',
                data: $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchUserCourses(CourseFilterDTO $courseFilterDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($courseFilterDTO);
            $courses = $this->courseRepository->getUserCourse();
            $resource = $this->HandelCourseResource(
                $courses,
                $view,
                $courseFilterDTO->with_lessons,
                $courseFilterDTO->paginate
            );
            // $resource = $view == ViewTypeEnum::WEBSITE->value ? CourseWebsiteApiResource::collection($courses) : ($courseFilterDTO->with_lessons ? FullCourseResource::collection($courses) : CourseResource::collection($courses));
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $courseFilterDTO->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchUserFavoriteCourses(CourseFilterDTO $courseDTO, $view = ViewTypeEnum::DASHBOARD->value): DataStatus
    {
        try {
            // dd($courseDTO);
            $courses = $this->courseRepository->getUserFavoriteCourse();
            $resource = $this->HandelCourseResource(
                $courses,
                $view,
                $courseDTO->with_lessons,
                $courseDTO->paginate
            );
            // $resource = $view == ViewTypeEnum::WEBSITE->value ? CourseWebsiteApiResource::collection($courses) : ($courseDTO->with_lessons ? FullCourseResource::collection($courses) : CourseResource::collection($courses));
            return DataSuccess(
                status: true,
                message: 'Courses fetched successfully',
                data: $courseDTO->paginate ? $resource->response()->getData() : $resource
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchCourseDetail(CourseDTO $courseDTO): DataStatus
    {
        try {
            $course = $this->courseRepository->getById($courseDTO->course_id);
            return DataSuccess(
                status: true,
                message: 'Course fetched successfully',
                data: new CourseTitleResource($course)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }



    public function createCourse(CourseDTO $courseDTO): DataStatus
    {
        try {
            // dd($courseDTO);
            $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
            $courseDTO->created_by = $this->employee->id;
            // dd($courseDTO);
            $course = $this->courseRepository->create($courseDTO);
            if (isset($courseDTO->video) || $courseDTO->video != null) {

                /** @var VideoDTO $videoDTO */
                $videoDTO = VideoDTO::fromArray($courseDTO->video);
                $videoDTO->videoable_id = $course->id;
                $videoDTO->videoable_type = Course::class;
                $video = $this->videoRepository->create($videoDTO);
            }
            if (isset($courseDTO->setting) || $courseDTO->setting != null) {
                /** @var CourseSettingDTO $settingDTO */
                $settingDTO = CourseSettingDTO::fromArray($courseDTO->setting);
                $settingDTO->course_id = $course->id;
                $this->courseSettingRepository->create($settingDTO);
            }
            if (isset($courseDTO->teacherIds) && count($courseDTO->teacherIds) > 0) {
                foreach ($courseDTO->teacherIds as $teacherId) {
                    $courseTeacherDTO = CourseTeacherDTO::fromArray([
                        'course_id' => $course->id,
                        'teacher_id' => $teacherId
                    ]);
                    $this->courseTeacherRepository->create($courseTeacherDTO);
                }
            }
            $course->refresh();
            if (isset($courseDTO->platforms) && count($courseDTO->platforms) > 0) {
                $this->createCoursePlatform($course->id, $courseDTO);
            }
            //            $coursePlatform = $this->createCoursePlatform($course->id, $courseDTO);
            if (isset($courseDTO->payment) && count($courseDTO->payment) > 0) {
                //                dd($courseDTO->payment);
                $this->createCoursePayment($course->id, $courseDTO->payment);
            }
            return DataSuccess(
                status: true,
                message: 'Course created successfully',
                data: $course
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    private function createCoursePlatform(int $courseId, CourseDTO $courseDTO)
    {
        foreach ($courseDTO->platforms ?? [] as $platform) {
            $coursePlatformDTO = CoursePlatformDTO::fromArray([
                'platform_id' => $platform['id'] ?? null,
                'course_id' => $courseId,
                'link' => $platform['link'] ?? null
            ]);
            // dd($coursePlatformDTO->toArray());
            $this->coursePlatformRepository->create($coursePlatformDTO);
        }
    }

    private function createCoursePayment($courseId, $payment)
    {

        // dd($payment);
        // dd($payment['is_paid']);
        $coursePaymentDTO = CoursePaymentDTO::fromArray([
            'course_id' => $courseId,
            'is_paid' => $payment['is_paid'] ?? 0,
            'price' => $payment['price'] ?? 0,
            'currency_id' => $payment['currency_id'] ?? null,
        ]);
        // dd($coursePaymentDTO->toArray());
        $this->coursePaymentRepository->create($coursePaymentDTO);
    }
    public function updateCourse(CourseDTO $courseDTO): DataStatus
    {
        try {
            $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
            $courseDTO->updated_by = $this->employee->id;
            $course = $this->courseRepository->update($courseDTO->course_id, $courseDTO);
            $courseDTO->course_id = $course->id;
            if (isset($courseDTO->video) || $courseDTO->video != null) {
                /** @var VideoDTO $videoDTO */
                $videoDTO = VideoDTO::fromArray($courseDTO->video);
                $videoDTO->video_id = $course->video->id;
                $video = $this->videoRepository->update($videoDTO->video_id, $videoDTO);
            }

            if (isset($courseDTO->setting) || $courseDTO->setting != null) {
                /** @var CourseSettingDTO $settingDTO */
                $settingDTO = CourseSettingDTO::fromArray($courseDTO->setting);
                $settingDTO->course_id = $course->id;
                $this->courseSettingRepository->updateOrCreate($settingDTO);
            }
            //   if (isset($courseDTO->teacherIds) && count($courseDTO->teacherIds) > 0) {

            //   }
            $course->refresh();
            return DataSuccess(
                status: true,
                message: 'Course updated successfully',
                data: new CourseResource($course)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteCourse(CourseDTO $courseDTO): DataStatus
    {
        try {
            $course = $this->courseRepository->getById($courseDTO->course_id);
            $subscriptions = $course->subscriptions()->where('status', SubscriptionStatusEnum::SUCCESS->value)->first();
            if ($subscriptions) {
                return DataFailed(
                    status: false,
                    message: 'Can not delete course with active subscription'
                );
            }
            $video_id = $this->courseRepository->getById($courseDTO->course_id)->video?->id;
            if ($video_id) {
                $this->videoRepository->delete($video_id);
            }
            $course = $this->courseRepository->delete($courseDTO->course_id);

            return DataSuccess(
                status: true,
                message: 'Course deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function addLevels(AddLevelDTO $addLevelDTO): DataStatus
    {
        try {
            foreach ($addLevelDTO->level_ids as $level_id) {
                $addLevelDTO->level_id = $level_id;
                $this->courseLevelRepository->create($addLevelDTO);
            }
            return DataSuccess(
                status: true,
                message: 'Levels added successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchLevelCourses(CourseLevelDTO $courseLevelDTO): DataStatus
    {
        try {
            // dd($courseLevelDTO);
            $levels = $this->courseLevelRepository->filter($courseLevelDTO);
            return DataSuccess(
                status: true,
                message: 'Levels fetched successfully',
                data: CourseLevelResource::collection($levels)->response()->getData()
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }


    private function HandelCourseResource($courses, $viewType, $with_lessons, $paginate)
    {
        if ($viewType == ViewTypeEnum::WEBSITE->value) {
            return CourseWebsiteApiResource::collection($courses);
        } elseif ($viewType == ViewTypeEnum::DASHBOARD->value) {
            if ($with_lessons) {
                return  FullCourseResource::collection($courses);
            } else {
                // dd($courses);
                return CourseResource::collection($courses);
            }
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            if ($with_lessons) {
                return  FullCourseResource::collection($courses);
            } else {
                return CourseResource::collection($courses);
            }
        } else if ($viewType == ViewTypeEnum::API->value) {
            return  ApiCourseResource::collection($courses);
        }
    }

    public function HandelCourseDetailResource($course, $viewType, $with_lessons, $paginate, $lesson_id, $onlyContent = false)
    {
        if ($viewType == ViewTypeEnum::WEBSITE->value  && $onlyContent) {
            return new WebsiteCourseContentResource($course);
        } else if ($viewType == ViewTypeEnum::WEBSITE->value && $lesson_id != null) {

            return new CourseDetailsForLessonResource($course, $lesson_id);
        } else if ($viewType == ViewTypeEnum::WEBSITE->value) {

            return new WebsiteCourseDetailsResource($course);
        } elseif ($viewType == ViewTypeEnum::DASHBOARD->value) {
            if ($with_lessons) {
                return new FullCourseDetailsResource($course);
            } else {
                return new CourseDetailsResource($course);
            }
        } elseif ($viewType == ViewTypeEnum::MOBILE->value) {
            if ($with_lessons) {
                return new FullCourseDetailsResource($course);
            } else {
                return new CourseDetailsResource($course);
            }
        }
    }

    public function checkSubscription(CourseDTO $CourseDto): DataStatus
    {
        try {
            $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
            $course = $this->courseRepository->getById($CourseDto->course_id);
            $subscription = $course->subscriptions()->where('user_id', $user->id)->first() ? true : false;
            return DataSuccess(
                status: true,
                message: 'Subscription found successfully',
                data: $subscription
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchCourseStatistics(CourseDTO $filterDto): DataStatus
    {
        try {
            $course = $this->courseRepository->getById($filterDto->course_id);
            return DataSuccess(
                status: true,
                message: 'Statistics fetched successfully',
                data: new CourseStatisticsResource($course)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchCourseOffers(CourseOfferDTO $filterDto): DataStatus
    {
        try {
            $offers = $this->courseOfferRepository->filter($filterDto);
            return DataSuccess(
                status: true,
                message: 'Offers fetched successfully',
                data: CourseOfferResource::collection($offers)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function addCourseOffer(CourseOfferDTO $courseOfferDTO): DataStatus
    {

        try {
            $this->employee = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::EMPLOYEE->value);
            $courseOfferDTO->created_by = $this->employee->id;
            // dd($courseOfferDTO);
            $course_offer = $this->courseOfferRepository->create($courseOfferDTO);
            $course = $this->courseRepository->getById($courseOfferDTO->course_id);
            $courseDTO =  CourseDTO::fromArray([
                'has_discount' => 1
            ]);
            $this->courseRepository->update($course->id, $courseDTO);
            return DataSuccess(
                status: true,
                message: 'offer created successfully',
                data: new CourseOfferResource($course_offer)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function deleteCourseOffer(CourseOfferDTO $courseOfferDTO): DataStatus
    {
        try {
            $this->courseOfferRepository->delete($courseOfferDTO->course_offer_id);
            return DataSuccess(
                status: true,
                message: 'offer deleted successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function toggleFavoriteCourse(CourseDTO $courseDTO): DataStatus
    {
        try {
            $this->courseRepository->toggleColumn([
                'id' => $courseDTO->course_id,
            ], 'has_favourite');
            return DataSuccess(
                status: true,
                message: 'Favorite course toggled successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
    public function toggleHiddenCourse(CourseDTO $courseDTO): DataStatus
    {
        try {
            $this->courseRepository->toggleColumn([
                'id' => $courseDTO->course_id,
            ], 'has_hidden');
            return DataSuccess(
                status: true,
                message: 'Hidden course toggled successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
