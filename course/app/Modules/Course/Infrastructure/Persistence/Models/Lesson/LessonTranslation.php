<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LessonTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'lesson_translations';
    protected $fillable = [
        'lesson_id',
        'locale',
        'title',
    ];
}
