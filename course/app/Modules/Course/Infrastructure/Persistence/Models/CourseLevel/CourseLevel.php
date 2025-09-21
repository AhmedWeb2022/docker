<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\CourseLevel;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Course\Infrastructure\Persistence\Models\Level\Level;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class CourseLevel extends Model
{
    protected $table = 'course_levels';
    protected $fillable = [
        'course_id',
        'level_id',
        'price',
        'payment_status',
    ];


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
