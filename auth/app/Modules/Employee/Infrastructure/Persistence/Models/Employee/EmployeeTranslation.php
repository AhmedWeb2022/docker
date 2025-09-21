<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'employee_translations';
    protected $fillable = [
        'employee_id',
        'locale',
        'title',
        'description',
    ];
}
