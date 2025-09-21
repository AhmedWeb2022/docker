<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\CourseOffer;

use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseOffer extends Model
{
    protected $fillable = [
        'course_id' ,'discount_type' , 'discount_amount' , 'discount_from_date' , 'discount_to_date'
    ];

    public function course():belongsTo
    {
        return $this->belongsTo(Course::class , 'course_id');
    }
}
