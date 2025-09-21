<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\SubscriptionHistory;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    protected $fillable = [
        'organization_id',
        'user_id',
        'subscription_id',
        'status',
        'price',
        'notes',
        'receipt',
    ];

    protected $table = 'subscription_histories';
}
