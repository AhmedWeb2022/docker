<?php

namespace App\Modules\Course\Application\UseCases\Subscription;

use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Application\Response\DataStatus;
use App\Modules\Course\Application\DTOS\Subscription\SubscriptionDTO;
use App\Modules\Course\Application\DTOS\Subscription\SubscriptionFilterDTO;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionTypeEnum;
use App\Modules\Course\Http\Resources\Subscription\Api\SubscriptionResource;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionStatusEnum;
use App\Modules\Course\Application\DTOS\SubscriptionHistory\SubscriptionHistoryDTO;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Subscription\SubscriptionRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\SubscriptionHistory\SubscriptionHistoryRepository;



class SubscriptionUseCase
{

    protected $subscriptionRepository;
    protected $subscriptionHistoryRepository;
    protected $courseRepository;


    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->subscriptionHistoryRepository = new SubscriptionHistoryRepository();
        $this->courseRepository = new CourseRepository();
    }

    public function subscribe(SubscriptionDTO $subscriptionDTO): DataStatus
    {
        try {
            $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
            if ($subscriptionDTO->is_join) {
                if ($subscriptionDTO->type == SubscriptionTypeEnum::COURSE->value) {
                    $course = $this->courseRepository->getById($subscriptionDTO->type_id);
                    $subscriptionDTO->status = $course->is_free ? SubscriptionStatusEnum::SUCCESS->value : SubscriptionStatusEnum::PENDING->value;
                } else {

                    $subscriptionDTO->status = SubscriptionStatusEnum::PENDING->value;
                }
                $subscriptionDTO->user_id == null ? $subscriptionDTO->user_id = $user->id : $subscriptionDTO->user_id;
                $subscription = $this->subscriptionRepository->create($subscriptionDTO);
                try {
                    $this->subscriptionRepository->sendNotification(
                        $subscriptionDTO,
                        'Your subscription has been created wait for admin approval.',
                        18
                    );
                } catch (\Exception $e) {
                }
            } else {
                $subscriptionDTO->user_id == null ? $subscriptionDTO->user_id = $user->id : $subscriptionDTO->user_id;
                $payment = $this->subscriptionRepository->getPaymentMethod($subscriptionDTO);
                if (!$payment) {
                    return DataFailed(
                        status: false,
                        message: 'Payment method not found'
                    );
                }
                $transaction = $this->subscriptionRepository->getTransaction($subscriptionDTO);
                if ($transaction['type'] != $subscriptionDTO->type || $transaction['type_id'] != $subscriptionDTO->type_id) {
                    return DataFailed(
                        status: false,
                        message: 'Transaction type and type id does not match'
                    );
                }
                if ($payment['is_online_payment']) {
                    // dd('online payment');
                    $transactionStatus = $this->subscriptionRepository->checkTransactionStatus($subscriptionDTO);
                    if ($transactionStatus['status'] == SubscriptionStatusEnum::SUCCESS->value) {
                        // dd('success');
                        $isExist = $this->subscriptionRepository->getMultibleWhere([
                            'user_id' => $subscriptionDTO->user_id,
                            'type' => $subscriptionDTO->type,
                            'type_id' => $subscriptionDTO->type_id,
                        ], 'first');
                        if ($isExist) {
                            return DataFailed(
                                status: false,
                                message: 'This subscription already exists'
                            );
                        }
                        $subscriptionDTO->status = SubscriptionStatusEnum::SUCCESS->value;
                        $subscription = $this->subscriptionRepository->create($subscriptionDTO);
                        $this->subscriptionRepository->completeTransactionSubscription($subscriptionDTO);
                    } else {
                        return DataFailed(
                            status: false,
                            message: 'Transaction failed'
                        );
                    }
                } elseif ($payment['required_reciept'] && $subscriptionDTO->has_receipt) {
                    // dd('receipt');
                    $subscriptionDTO->status = SubscriptionStatusEnum::PENDING->value;
                    $subscription = $this->subscriptionRepository->create($subscriptionDTO);
                    if ($subscription) {
                        $subscriptionDTO->subscription_id = $subscription->id;
                        $history = SubscriptionHistoryDTO::fromArray([
                            'user_id' => $user->id,
                            'subscription_id' => $subscriptionDTO->subscription_id,
                            'receipt' => $subscriptionDTO->receipt,
                            'price' => $subscriptionDTO->price,
                        ]);

                        $this->subscriptionHistoryRepository->create($history);

                        $this->subscriptionRepository->sendNotification(
                            $subscriptionDTO,
                            'Your subscription has been created wait for admin approval.',
                            18
                        );
                    }
                } else {
                    // dd('no receipt');
                    $subscriptionDTO->status = SubscriptionStatusEnum::SUCCESS->value;
                    $subscription = $this->subscriptionRepository->create($subscriptionDTO);
                    try {
                        $this->subscriptionRepository->sendNotification(
                            $subscriptionDTO,
                            'Your subscription has been created.',
                            17
                        );
                    } catch (\Exception $e) {
                    }
                }
            }

            return DataSuccess(
                status: true,
                message: 'Subscription created successfully',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            );
        }
    }


    public function changeSubscriptionStatus(SubscriptionDTO $subscriptionDTO): DataStatus
    {
        try {
            $subscription = $this->subscriptionRepository->update($subscriptionDTO->subscription_id, $subscriptionDTO);
            return DataSuccess(
                status: true,
                message: 'Subscription updated successfully',
                data: true
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function getUserIds(SubscriptionDTO $subscriptionDTO): DataStatus
    {
        try {
            $subscription = $this->subscriptionRepository->getMultibleWhere([
                'type' => $subscriptionDTO->type,
                'type_id' => $subscriptionDTO->type_id,
            ]);
            $userIds = $subscription->pluck('user_id');
            if (empty($userIds)) {
                return DataFailed(
                    status: false,
                    message: 'No users found for this subscription type'
                );
            }
            return DataSuccess(
                status: true,
                message: 'User IDs fetched successfully',
                data: $userIds
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function checkSubscription(SubscriptionDTO $subscriptionDTO): DataStatus
    {
        try {
            $subscription = $this->subscriptionRepository->getMultibleWhere([
                'type' => $subscriptionDTO->type,
                'type_id' => $subscriptionDTO->type_id,
                'user_id' => $subscriptionDTO->user_id,
            ], 'first');
            if (!$subscription) {
                return DataFailed(
                    status: false,
                    message: 'No subscription found for this user'
                );
            }
            return DataSuccess(
                status: true,
                message: 'Subscription found successfully',
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }

    public function fetchSubsciptions(SubscriptionFilterDTO $subscriptionFilterDTO): DataStatus
    {
        try {
            // $subscriptionFilterDTO->status = SubscriptionStatusEnum::PENDING->value;
            $subscriptions = $this->subscriptionRepository->filter(
                dto: $subscriptionFilterDTO,
                paginate: $subscriptionFilterDTO->paginate,
                limit: $subscriptionFilterDTO->limit,
            );
            return DataSuccess(
                status: true,
                message: 'Subscriptions fetched successfully',
                data: $subscriptionFilterDTO->paginate ? SubscriptionResource::collection($subscriptions)->response()->getData(true) : SubscriptionResource::collection($subscriptions)
            );
        } catch (\Exception $e) {
            return DataFailed(
                status: false,
                message: $e->getMessage()
            );
        }
    }
}
