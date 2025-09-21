<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma;

use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Rate\Rate;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaAbout\DiplomaAbout;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContent\DiplomaContent;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContentCourse\DiplomaContentCourse;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel\DiplomaLevel;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevelTrack\DiplomaLevelTrack;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaTarget\DiplomaTarget;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionContent\WebsiteSectionContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Diploma extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = [
        'title',
        'short_description',
        'full_description',
    ];

    protected $table = 'diplomas';
    public const LANGUAGES = ['ar', 'en'];
    protected $fillable = [
        'main_image',
        'start_date',
        'end_date',
        'target',
        'number_of_corse',
        'has_level',
        'has_track',
        'created_by',
        'updated_by',
        'language',
        'diploma_specialization',
    ];

    protected function imageLink(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->main_image ? asset('storage/' . $this->main_image) : '',
        );
    }

    public function websiteSectionContents()
    {
        return $this->morphMany(WebsiteSectionContent::class, 'contentable');
    }
    public function levels()
    {
        return $this->hasMany(DiplomaLevel::class, 'diploma_id');
    }

    public function tracks()
    {
        return $this->hasMany(DiplomaLevelTrack::class, 'diploma_id');
    }

    public function contents()
    {
        return $this->hasMany(DiplomaContent::class, 'diploma_id');
    }

    public function diplomaContentCourses()
    {
        return $this->hasMany(DiplomaContentCourse::class, 'diploma_id');
    }


    public function targets()
    {
        return $this->hasMany(DiplomaTarget::class, 'diploma_id');
    }

    public function abouts()
    {
        return $this->hasMany(DiplomaAbout::class, 'diploma_id');
    }

    // public function courses()
    // {
    //     return $this->belongsToMany(Course::class, 'diploma_content_courses', 'diploma_id', 'course_id');
    // }

    public function rates()
    {
        return $this->morphMany(Rate::class, 'rateable');
    }

}
