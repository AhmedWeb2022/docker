<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Models\EmployeeDevice;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee;

class EmployeeDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'device_id',
        'device_type',
        'device_token',
        'device_os',
        'device_os_version',
        'device_model',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
