<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\CourseDependency;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use App\Modules\Course\Infrastructure\Persistence\Models\Platform\Platform;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class CoursePlatform extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'platform_id',
        'link',
        'image',
        'cover'
    ];

    protected $table = 'course_platforms';
    protected $appends  = ["image_link", "cover_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function getCoverLinkAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : '';
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }
}
