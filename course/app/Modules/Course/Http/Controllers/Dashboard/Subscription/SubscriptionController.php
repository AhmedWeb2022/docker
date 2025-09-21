<?php

namespace App\Modules\Course\Http\Controllers\Dashboard\Subscription;

use App\Modules\Course\Application\UseCases\Subscription\SubscriptionUseCase;
use App\Modules\Course\Http\Requests\Global\Subscription\FetchSucscriptionsRequest;
use App\Modules\Course\Http\Requests\Global\Subscription\ChangeSubscriptionStatusRequest;


class SubscriptionController
{
    protected $subscriptionUseCase;
    public function __construct(SubscriptionUseCase $subscriptionUseCase)
    {
        $this->subscriptionUseCase = $subscriptionUseCase;
    }

    public function changeSubscriptionStatus(ChangeSubscriptionStatusRequest $request)
    {
        return $this->subscriptionUseCase->changeSubscriptionStatus($request->toDTO())->response();
    }

    public function fetchSubsciptions(FetchSucscriptionsRequest $request)
    {
        return $this->subscriptionUseCase->fetchSubsciptions($request->toDTO())->response();
    }
}
