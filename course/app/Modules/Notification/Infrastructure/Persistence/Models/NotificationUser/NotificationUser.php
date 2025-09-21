<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Models\NotificationUser;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $table = 'notification_users';

    protected $fillable = [
        'notification_id',
        'user_id',
        'topic_id',
        'is_read',
    ];
}
