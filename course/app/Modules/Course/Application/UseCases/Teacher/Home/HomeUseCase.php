<?php

namespace App\Modules\Course\Application\UseCases\Teacher\Home;

use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\CourseTeacher\CourseTeacherDTO;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Subscription\SubscriptionRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\CourseTeacher\CourseTeacherRepository;


class HomeUseCase
{

    protected $courseTecherRepository;
    protected $subscriptionRepository;
    public function __construct(CourseTeacherRepository $courseTecherRepository)
    {
        $this->courseTecherRepository = $courseTecherRepository;
        $this->subscriptionRepository = new  SubscriptionRepository();
    }

    public function fetchTeacherCoursesUserIds(CourseTeacherDTO $courseTeacherDTO): DataStatus
    {
        try {
            $course_ids = $this->courseTecherRepository->getWhere('teacher_id', $courseTeacherDTO->teacher_id)->pluck('course_id')->toArray();
            if (empty($course_ids)) {
                return DataFailed(
                    status: false,
                    message: 'No courses found for the teacher'
                );
            }
            // dd($course_ids);
            $userIds = $this->subscriptionRepository->getMultibleWhere([
                'type' => SubscriptionTypeEnum::COURSE->value,
                'type_id' => $course_ids,
            ])->pluck('user_id')->toArray();

            return DataSuccess(
                status: true,
                message: 'Teacher courses user IDs fetched successfully',
                data: $userIds
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
