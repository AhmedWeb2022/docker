<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Document;

use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Document extends Model implements TranslatableContract
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
        'document_type',
        'image',
    ];
    public $translatedAttributes = ['title', 'description'];
    protected $translationForeignKey = 'document_id';
    protected $table = 'documents';
    protected $appends  = ["image_link", "file_link"];


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
        return $this->hasMany(Document::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}
