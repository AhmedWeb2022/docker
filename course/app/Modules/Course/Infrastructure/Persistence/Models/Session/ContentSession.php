<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Session;

use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class ContentSession extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'parent_id',
        'organization_id',
        'content_id',
        'is_file',
        'link',
        'file',
        'status',
        'session_type',
        'image',
        'can_skip',
        'skip_rate',

    ];
    public $translatedAttributes = ['title', 'description'];
    protected $translationForeignKey = 'session_id';
    protected $table = 'content_sessions';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function getFileLinkAttribute()
    {
        return $this->file ? asset('storage/' . $this->file) : '';
    }



    public function children()
    {
        return $this->hasMany(ContentSession::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(ContentSession::class, 'parent_id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}
