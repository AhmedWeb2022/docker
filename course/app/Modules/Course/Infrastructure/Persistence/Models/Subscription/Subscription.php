<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Subscription;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Subscription\SubscriptionRepository;

class Subscription extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'type_id',
        'type',
        'number',
        'payment_method_id',
        'accepted_by',
        'rejected_by',
        'start_date',
        'end_date',
        'has_end_date',
        'paid_status',
        'status',
        'price',
        'notes',
    ];

    protected $table = 'subscriptions';


    public function user()
    {
        $subscriptionRepository = new SubscriptionRepository();
        return $subscriptionRepository->getUser($this->user_id);
    }

    public function getCourseAttribute()
    {
        if ($this->type === SubscriptionTypeEnum::COURSE->value) {
            return Course::find($this->type_id);
        }

        return null;
    }
}
