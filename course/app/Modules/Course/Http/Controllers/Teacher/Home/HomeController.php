<?php

namespace App\Modules\Course\Http\Controllers\Teacher\Home;



use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Http\Resources\Content\ContentResource;
use App\Modules\Course\Application\DTOS\Teacher\Group\GroupDTO;
use App\Modules\Course\Application\DTOS\Teacher\Course\CourseDTO;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Application\DTOS\Teacher\Content\ContentDTO;
use App\Modules\Course\Http\Resources\Teacher\Course\CourseResource;
use App\Modules\Course\Application\UseCases\Teacher\Home\HomeUseCase;
use App\Modules\Course\Application\DTOS\CourseTeacher\CourseTeacherDTO;
use App\Modules\Course\Application\UseCases\Teacher\Group\GroupUseCase;
use App\Modules\Course\Http\Requests\Teacher\Course\FetchCourseRequest;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Application\UseCases\Teacher\Course\CourseUseCase;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionTypeEnum;
use App\Modules\Course\Application\UseCases\Teacher\Content\ContentUseCase;
use App\Modules\Course\Application\DTOS\Teacher\Subscription\SubscriptionDTO;
use App\Modules\Course\Application\UseCases\Teacher\Subscription\SubscriptionUseCase;

class HomeController extends Controller
{
    protected $courseUseCase;
    protected $groupUseCase;
    protected $subscriptionUseCase;
    protected $contentUseCase;
    protected $homeUseCase;
    protected $teacher;

    public function __construct(
        CourseUseCase $courseUseCase,
        GroupUseCase $groupUseCase,
        SubscriptionUseCase $subscriptionUseCase,
        ContentUseCase $contentUseCase,
        HomeUseCase $homeUseCase,
    ) {
        $this->courseUseCase = $courseUseCase;
        $this->groupUseCase = $groupUseCase;
        $this->subscriptionUseCase = $subscriptionUseCase;
        $this->contentUseCase = $contentUseCase;
        $this->homeUseCase = $homeUseCase;
        $this->teacher = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::TEACHER->value);
    }

    public function fetchStatistics()
    {
        // Course Statistics
        $courseDTO = new CourseDTO();
        $courseDTO->teacher_id = $this->teacher->id;
        /** @var Course $courses */
        $courses = $this->courseUseCase->fetchHomeCourses($courseDTO)->getData();

        // Group Statistics
        $groupDTO = new GroupDTO();
        $groupDTO->course_id = $courses->pluck('id')->toArray();
        /** @var Group $groups */
        $groups = $this->groupUseCase->fetchGroups($groupDTO)->getData();

        // Subscription Statistics
        $subscriptionDTO = new SubscriptionDTO();
        $subscriptionDTO->type = SubscriptionTypeEnum::COURSE->value;
        $subscriptionDTO->type_id = $courses->pluck('id')->toArray();
        /** @var Subscription $subscriptions */
        $subscriptions = $this->subscriptionUseCase->fetchSubsciptions($subscriptionDTO)->getData();
        $subscriptions = $subscriptions->unique(function ($item) {
            return $item->user_id . '-' . $item->type_id . '-' . $item->type;
        })->values();


        // Content Statistics
        $contentDTO = new ContentDTO();
        $contentDTO->course_id = $courses->pluck('id')->toArray();
        $contentDTO->group_id = $groups->pluck('id')->toArray();
        $contentDTO->type = ContentTypeEnum::LIVE->value;
        $contentDTO->teacher_id = $this->teacher->id;
        /** @var Content $contents */
        $contents = $this->contentUseCase->fetchContents($contentDTO)->getData();
        // dd($content);
        $isGoingLive = $contents->filter(function ($item) {
            // dd($item->live);
            return $item->live->is_going_live;
        })->first();


        //student_months statistics
        // Step 1: Define months up to current month
        $months = collect(range(1, now()->month))->mapWithKeys(function ($month) {
            return [Carbon::create()->month($month)->format('M') => 0]; // e.g., 'Jan' => 0
        });

        // Step 2: Filter subscriptions for current year only
        $currentYear = now()->year;
        $filtered = $subscriptions->filter(function ($subscription) use ($currentYear) {
            return optional($subscription->created_at)->year === $currentYear;
        });

        // Step 3: Group and count subscriptions by month
        $grouped = $filtered->groupBy(function ($subscription) {
            return Carbon::parse($subscription->created_at)->format('M');
        })->map->count();

        // Step 4: Merge counts into month list
        $final = $months->merge($grouped)->map(function ($count, $month) {
            return [
                'month' => $month,
                'students' => $count,
            ];
        })->values();

        $resource = [
            "student_joined" => $subscriptions->count(),
            "total_courses" => $courses->count(),
            "total_groups" => $groups->count(),
            "total_lives" => $contents->count(),
            'live' => $isGoingLive ?  new ContentResource($isGoingLive) : [],
            'lives' => ContentResource::collection($contents),
            "courses" => CourseResource::collection($courses),
            'student_months' => $final
        ];

        return response()->json([
            'status' => true,
            'message' => 'Statistics fetched successfully',
            'data' => $resource
        ]);
    }

    public function fetchTeacherCoursesUserIds()
    {
        $courseTecherDTO = new CourseTeacherDTO();
        $courseTecherDTO->teacher_id = $this->teacher->id;
        return $this->homeUseCase->fetchTeacherCoursesUserIds($courseTecherDTO)->response();
    }
}
