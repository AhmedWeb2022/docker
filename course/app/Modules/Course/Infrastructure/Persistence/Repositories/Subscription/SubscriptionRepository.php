<?php

namespace App\Modules\Course\Infrastructure\Persistence\Repositories\Subscription;



use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Base\Domain\DTO\BaseDTOInterface;
use App\Modules\Base\Domain\Repositories\BaseRepositoryAbstract;
use App\Modules\Course\Application\DTOS\Subscription\SubscriptionDTO;

use App\Modules\Course\Application\Enums\Subscription\SubscriptionStatusEnum;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\UserApiService;
use App\Modules\Course\Infrastructure\Persistence\Models\Subscription\Subscription;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Payment\PaymentApiService;
use App\Modules\Notification\Infrastructure\Persistence\ApiService\NotificationApiService;

class SubscriptionRepository extends BaseRepositoryAbstract
{

    protected $paymentApiService;
    protected $notificationApiService;
    protected $userApiService;
    public function __construct()
    {
        $this->setModel(new Subscription());
        $this->paymentApiService = new PaymentApiService();
        $this->notificationApiService = new NotificationApiService();
        $this->userApiService = new UserApiService();
    }


    public function create(BaseDTOInterface $dto): Model
    {
        try {
            DB::beginTransaction();

            $model = $this->getModel()->create($dto->toArray());
            DB::commit();
            $model->refresh();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            deleteFilesFromDto($dto->toArray());
            report($e);
            return $this->getModel(); // Return a new instance of the model in case of failure
        }
    }

    public function getPaymentMethod($dto)
    {
        $payment = $this->paymentApiService->getPaymentMethod($dto)['data'];
        // dd($payment);
        if ($payment) {
            return $payment;
        } else {
            return null;
        }
    }
    public function getTransaction($dto)
    {
        $transaction = $this->paymentApiService->getTransaction($dto)['data'];
        // dd($transaction);
        if ($transaction) {
            return $transaction;
        } else {
            return null;
        }
    }

    public function checkTransactionStatus($dto)
    {
        $transactionStatus = $this->paymentApiService->getTransactionStatus($dto)['data'];
        // dd($transactionStatus);
        if ($transactionStatus) {
            return $transactionStatus;
        } else {
            return null;
        }
    }

    public function completeTransactionSubscription($dto)
    {
        $transactionStatus = $this->paymentApiService->completeTransactionSubscription($dto)['data'];
        if ($transactionStatus) {
            return $transactionStatus;
        } else {
            return null;
        }
    }

    public function sendNotification($dto, $body, $notification_type)
    {
        $this->notificationApiService->sendNotification([
            'title' => 'Subscription',
            'subtitle' => 'Subscription Created',
            'body' => $body,
            'type' => 'subscription',
            'type_id' => $dto->subscription_id,
            'notification_type' => $notification_type,
            'user_ids' => [$dto->user_id],
        ]);
    }

    public function getUser($id)
    {
        return $this->userApiService->getUser($id);
    }
}
