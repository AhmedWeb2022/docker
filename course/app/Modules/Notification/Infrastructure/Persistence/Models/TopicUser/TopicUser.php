<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Models\TopicUser;

use Illuminate\Database\Eloquent\Model;

class TopicUser extends Model
{
    protected $table = 'topic_users';

    protected $fillable = [
        'topic_id',
        'type',
        'user_id',
    ];
}
