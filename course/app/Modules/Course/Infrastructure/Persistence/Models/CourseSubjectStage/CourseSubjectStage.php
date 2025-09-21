<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\CourseSubjectStage;

use Illuminate\Database\Eloquent\Model;

class CourseSubjectStage extends Model
{
    protected $table = 'course_subject_stages';
    protected $fillable = [
        'course_id',
        'subject_stage_id',
    ];
}
