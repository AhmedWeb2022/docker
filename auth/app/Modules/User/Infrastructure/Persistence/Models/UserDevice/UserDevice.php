<?php

namespace App\Modules\User\Infrastructure\Persistence\Models\UserDevice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\User\Infrastructure\Persistence\Models\User\User;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'device_type',
        'device_token',
        'device_os',
        'device_os_version',
        'device_model',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
