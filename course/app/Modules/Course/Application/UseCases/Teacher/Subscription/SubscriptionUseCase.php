<?php

namespace App\Modules\Course\Application\UseCases\Teacher\Subscription;


use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Teacher\Subscription\SubscriptionDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Subscription\SubscriptionRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\SubscriptionHistory\SubscriptionHistoryRepository;



class SubscriptionUseCase
{

    protected $subscriptionRepository;
    protected $subscriptionHistoryRepository;


    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionHistoryRepository = new SubscriptionHistoryRepository();
    }

    public function fetchSubsciptions(SubscriptionDTO $subscriptionDTO): DataStatus
    {
        try {
            // $subscriptionFilterDTO->status = SubscriptionStatusEnum::PENDING->value;
            $subscriptions = $this->subscriptionRepository->filter(
                dto: $subscriptionDTO,
                paginate: $subscriptionDTO->paginate,
                limit: $subscriptionDTO->limit,
            );
            return DataSuccess(
                status: true,
                message: 'Subscriptions fetched successfully',
                data: $subscriptions
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
