<?php

namespace App\Modules\Admin\Infrastructure\Persistence\Models\AdminDevice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Admin\Infrastructure\Persistence\Models\Admin\Admin;

class AdminDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'device_id',
        'device_type',
        'device_token',
        'device_os',
        'device_os_version',
        'device_model',
    ];
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
