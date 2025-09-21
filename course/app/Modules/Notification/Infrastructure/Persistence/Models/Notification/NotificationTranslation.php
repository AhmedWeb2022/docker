<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Models\Notification;

use Illuminate\Database\Eloquent\Model;

class NotificationTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'notification_translations';

    protected $fillable = ['title', 'subtitle', 'description'];

}
