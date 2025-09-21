<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Models\Notification;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Auth\Infrastructure\Persistence\Models\Customer\User;
use App\Modules\Notification\Infrastructure\Persistence\Models\NotificationUser\NotificationUser;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Notification extends Model
{
    use HasFactory;

    // public $translatedAttributes = ['title', 'subtitle', 'description'];

    protected $table = 'notifications';

    protected $fillable = [
        'image',
        'url',
        'title',
        'subtitle',
        'notifiable_id',
        'notifiable_type',
    ];

    public function ImageLink(): Attribute
    {
        if (isset($this->image) &&  Storage::disk('public')->exists($this->image)) {
            return Attribute::make(fn() => asset('storage/' . $this->image));
        } else {
            return Attribute::make(fn() => "");
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_users', 'notification_id', 'user_id')->withPivot('notification_id', 'user_id', 'is_read');
    }
    
    public function notification_users()
    {
        return $this->hasMany(NotificationUser::class, 'notification_id');
    }


    public function notifiable()
    {
        return $this->morphTo();
    }

    public function userNotifications()
    {
        return $this->hasMany(NotificationUser::class);
    }
    public function isRead($user)
    {
        return $this->userNotifications()->where('user_id', $user->id)->first();
    }
}
