<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Course;


use Exception;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Modules\Base\Domain\Holders\AuthHolder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionStatusEnum;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Stage\StageApiService;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\EmployeeApiService;
use App\Modules\Course\Infrastructure\Persistence\Repositories\CourseTeacher\CourseTeacherRepository;

class CourseRepository extends BaseRepositoryAbstract
{

    protected $stageApiService;
    protected $employeeApiService;
    protected $courseTeacherRepository;
    public function __construct()
    {
        $this->setModel(new Course());
        $this->stageApiService = new StageApiService();
        $this->employeeApiService = new EmployeeApiService();
        $this->courseTeacherRepository = new CourseTeacherRepository();
    }

    public function filter(
        BaseDTOInterface $dto,
        string $operator = 'like',
        array $translatableFields = [],
        array $searchableFields = [],
        $paginate = false,
        $limit = 10,
        array $whereHasRelations = [],
        array $whereHasMultipleRelations = []
    ): Collection|LengthAwarePaginator {
        try {
            $query = $this->getModel()->query();
            // dd($query->get());
            // Handle parent_id condition
            $query->when(
                isset($dto->parent_id) && $dto->parent_id !== null,
                fn($q) => $q->where('parent_id', $dto->parent_id),
                fn($q) => $q->whereNull('parent_id')
            );
            // dd($query->get());
            // Filter by related setting.price (price_from and price_to)
            if (
                (isset($dto->price_from) && $dto->price_from !== null) ||
                (isset($dto->price_to) && $dto->price_to !== null)
            ) {
                $query->whereHas('coursePayment', function ($q) use ($dto) {
                    if (isset($dto->price_from) && $dto->price_from !== null) {
                        $q->where('price', '>=', $dto->price_from);
                    }
                    if (isset($dto->price_to) && $dto->price_to !== null) {
                        $q->where('price', '<=', $dto->price_to);
                    }
                });
            }
            // dd($query->get());
            // Full-text search on translatable fields
            if (!empty($dto->word)) {
                $query->where(function ($q) use ($dto) {
                    $q->whereTranslationLike('title', "%{$dto->word}%")
                        ->orWhereTranslationLike('description', "%{$dto->word}%");
                });
            }
            // dd($query->get());

            if (isset($dto->rate) && !empty($dto->rate)) {
                if (is_array($dto->rate) && count($dto->rate) === 2) {
                    $min = (float) $dto->rate[0];
                    $max = (float) $dto->rate[1] + 0.9;
                } else {
                    $rate = (float) (is_array($dto->rate) ? $dto->rate[0] : $dto->rate);
                    $min = $rate;
                    $max = $rate + 0.9;
                }

                $query->withAvg(['rates as avg_rate' => function ($q) {
                    $q->where('rateable_type', Course::class);
                }], 'rate')
                    ->havingBetween('avg_rate', [$min, $max]);
            }





            // dd($query->get());
            // Loop over DTO filters
            foreach ($dto->toArray() as $key => $value) {
                if (in_array($key, ['teacher_id', 'price_from', 'price_to', 'word']) || $value === null) {
                    continue;
                }

                // dd($query->get());

                if (!in_array($key, ['lat', 'lng', 'distance']) && filled($value)) {
                    $query
                        ->when(in_array($key, $translatableFields), fn($q) => $q->whereTranslationLike($key, "%{$value}%"))
                        ->when(is_array($value) && !in_array($key, $translatableFields), fn($q) => $q->whereIn($key, $value))
                        ->when(is_bool($value), fn($q) => $q->where($key, $value))
                        ->when(is_numeric($value), fn($q) => $q->where($key, $operator, $value))
                        ->when(
                            is_string($value) && !in_array($key, $translatableFields),
                            fn($q) => $q->where($key, $operator, $operator === 'like' ? "%{$value}%" : $value)
                        );
                }
            }
            // dd($query->get());
            if (isset($dto->has_discount) && $dto->has_discount == 1) {
                $now = Carbon::now();
                $query->whereHas('offers', function ($q) use ($now) {
                    $q->where('discount_from_date', '<=', $now)
                        ->where('discount_to_date', '>=', $now);
                });
            }
            // dd($query->get());
            // Apply whereHas relations
            foreach ($whereHasRelations as $relation => $conditions) {

                // Case 1: If relation is passed as a string with no conditions
                if (is_int($relation) && is_string($conditions)) {

                    $query->whereHas($conditions);
                    continue;
                }

                // Case 2: If relation conditions is a closure
                if (is_callable($conditions)) {

                    $query->whereHas($relation, $conditions);
                    continue;
                }

                // Case 3: Default array-based logic (your original code)
                if (is_array($conditions)) {


                    foreach ($conditions as $key => $values) {
                        // dd($values);
                        if (is_array($values)) {
                            if ($values !== []) {
                                $query->whereHas($relation, function ($q) use ($key, $values) {
                                    $q->whereIn($key, $values);
                                });
                            }
                        } elseif (is_numeric($values)) {
                            // dd($values);
                            if ($values !== null) {
                                $query->whereHas($relation, function ($q) use ($key, $values) {
                                    $q->where($key, $values);
                                });
                            }
                        }
                    }
                }
            }

            // dd($query->get());
            // Optional: whereHasMultipleRelations support (commented out for now)
            // foreach ($whereHasMultipleRelations as $relationsGroup) {
            //     foreach ($relationsGroup as $relation => $conditions) {
            //         $query->whereHas($relation, function ($q) use ($conditions) {
            //             foreach ($conditions as $key => $values) {
            //                 if (is_array($values)) {
            //                     $q->whereIn($key, $values);
            //                 } else {
            //                     $q->where($key, '=', $values);
            //                 }
            //             }
            //         });
            //     }
            // }

            return $paginate
                ? $query->paginate($limit ?: 10)
                : $query->get();
        } catch (\Exception $e) {
            report($e);
            return collect();
        }
    }



    public function getStage($courseId)
    {
        try {
            $course = $this->getWhere('id', $courseId, 'first');
            // dd($course);
            if (!$course) {
                throw new \Exception('course not found.');
            }
            $response = $this->stageApiService->fetchStageDetails($course->stage_id);
            // dd($response);
            if ((isset($response['success']) && !$response['success']) || (isset($response['status']) && !$response['status'])) {
                return null;
            }
            return $response['data'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getSubject($courseId)
    {
        try {
            $course = $this->getWhere('id', $courseId, 'first');
            // dd($course);
            if (!$course) {
                throw new \Exception('course not found.');
            }
            $response = $this->stageApiService->fetchSubjectDetails($course->subject_id);
            // dd($response);
            if ((isset($response['success']) && !$response['success']) || (isset($response['status']) && !$response['status'])) {
                return null;
            }
            // dd('not found');
            return $response['data'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getTeachers($courseId)
    {
        try {
            // dd($courseId);
            $course = $this->getWhere('id', $courseId, 'first');

            if (!$course) {
                throw new \Exception('course not found.');
            }
            // $teacherIds = $this->courseTeacherRepository->getWhere('course_id', $courseId)->pluck('teacher_id')->toArray();
            $teacherIds = $course->courseTeachers->pluck('teacher_id')->toArray();
            if ($teacherIds == null) {
                return null;
            }
            $response = $this->employeeApiService->fetchTeachers($teacherIds);
            if ((isset($response['success']) && !$response['success']) || (isset($response['status']) && !$response['status'])) {
                return null;
            }
            return $response['data'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getUserCourse()
    {
        try {
            $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
            // dd($user);
            $courses = Course::whereHas('subscriptions', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', SubscriptionStatusEnum::SUCCESS->value);
            })->get();

            return $courses;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getUserFavoriteCourse()
    {
        try {
            $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
            // dd($user);
            $courses = Course::whereHas('favorites', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
            return $courses;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
