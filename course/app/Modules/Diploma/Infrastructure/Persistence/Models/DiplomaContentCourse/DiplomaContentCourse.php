<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContentCourse;

use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DiplomaContentCourse extends Model
{
    use HasFactory;
    protected $table = 'diploma_content_courses';
    protected $fillable = [
        'diploma_content_id',
        'course_id',
    ];
    public function Diploma()
    {
        return $this->belongsTo(Diploma::class, 'diploma_id');
    }
}
