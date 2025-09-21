<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Level;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseLevel\CourseLevel;

class Level extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'image',
        'parent_id',
        'is_standalone',
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'level_id';
    protected $table = 'levels';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_levels', 'level_id', 'course_id');
    }

    public function CourseLevels()
    {
        return $this->hasMany(CourseLevel::class, 'level_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'level_id');
    }

    public function parent()
    {
        return $this->belongsTo(Level::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Level::class, 'parent_id');
    }
}
