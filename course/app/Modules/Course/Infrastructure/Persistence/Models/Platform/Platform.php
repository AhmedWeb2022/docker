<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Platform;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Platform extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'course_id',
        'image',
        'cover',
        'link',
        'slug',
    ];
    public $translatedAttributes = ['title', 'description'];
    protected $translationForeignKey = 'platform_id';
    protected $table = 'platforms';
    protected $appends  = ["image_link", "cover_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function getCoverLinkAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : '';
    }
}
