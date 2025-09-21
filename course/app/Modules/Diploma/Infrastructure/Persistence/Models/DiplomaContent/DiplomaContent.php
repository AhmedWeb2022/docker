<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContent;

use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DiplomaContent extends Model
{
    use HasFactory;
    protected $table = 'diploma_contents';
    protected $fillable = [
        'diploma_id',
        'diploma_level_track_id',
        'diploma_level_id',
        'order',
    ];
            public function Diploma()
    {
        return $this->belongsTo(Diploma::class,  'diploma_id');
    }

        public function courses()
    {
        return $this->belongsToMany(Course::class, 'diploma_content_courses', 'diploma_content_id', 'course_id');
    }
}
