<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Models\EmployeeSubjectStage;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Infrastructure\Persistence\Models\Employee\Employee;

class EmployeeSubjectStage extends Model
{
    protected $table = 'employee_subject_stages';

    protected $fillable = [
        'employee_id',
        'subject_stage_id',
        'subject_id',
        'stage_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
