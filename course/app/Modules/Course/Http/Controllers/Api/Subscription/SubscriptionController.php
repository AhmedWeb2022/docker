<?php

namespace App\Modules\Course\Http\Controllers\Api\Subscription;

use App\Modules\Course\Application\UseCases\Subscription\SubscriptionUseCase;
use App\Modules\Course\Http\Requests\Global\Subscription\SubscriptionRequest;
use App\Modules\Course\Http\Requests\Global\Subscription\checkSubscriptionRequest;
use App\Modules\Course\Http\Requests\Global\Subscription\FetchSubscriptionByTypeRequest;
use App\Modules\Course\Http\Requests\Global\Subscription\ChangeSubscriptionStatusRequest;


class SubscriptionController
{
    protected $subscriptionUseCase;
    public function __construct(SubscriptionUseCase $subscriptionUseCase)
    {
        $this->subscriptionUseCase = $subscriptionUseCase;
    }

    public function subscribe(SubscriptionRequest $request)
    {
        return $this->subscriptionUseCase->subscribe($request->toDTO())->response();
    }

    public function changeSubscriptionStatus(ChangeSubscriptionStatusRequest $request)
    {
        return $this->subscriptionUseCase->changeSubscriptionStatus($request->toDTO())->response();
    }

    public function getUserIds(FetchSubscriptionByTypeRequest $request)
    {
        return $this->subscriptionUseCase->getUserIds($request->toDTO())->response();
    }

    public function checkSubscription(checkSubscriptionRequest $request)
    {
        return $this->subscriptionUseCase->checkSubscription($request->toDTO())->response();
    }

    public function fetchTeacherCoursesUserIds(FetchSubscriptionByTypeRequest $request)
    {
        return $this->subscriptionUseCase->fetchTeacherCoursesUserIds($request->toDTO())->response();
    }
}
