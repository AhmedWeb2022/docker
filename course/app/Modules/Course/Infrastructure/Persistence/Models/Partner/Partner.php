<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Partner;

use App\Modules\Course\Infrastructure\Persistence\Models\Certificate\Certificate;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Partner extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'image',
        'cover',
        'link',
        'is_website',
    ];
    public $translatedAttributes = ['title', 'description'];
    protected $translationForeignKey = 'partner_id';
    protected $table = 'partners';
    protected $appends  = ["image_link", "cover_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function getCoverLinkAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : '';
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'partner_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'partner_id', 'id');
    }
}
