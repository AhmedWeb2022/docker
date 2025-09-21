<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Course;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'course_translations';
    protected $fillable = [
        'course_id',
        'locale',
        'title',
        'description',
        'card_description',
    ];
}
