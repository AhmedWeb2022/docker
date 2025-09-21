<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\CourseTeacher;

use Illuminate\Database\Eloquent\Model;

class CourseTeacher extends Model
{
    protected $table = 'course_teachers';
    protected $fillable = [
        'course_id',
        'teacher_id',
    ];
}
