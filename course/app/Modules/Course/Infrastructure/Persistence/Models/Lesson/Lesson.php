<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

// use Attribute;
// use Dom\Attr;

class Lesson extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'parent_id',
        'course_id',
        'organization_id',
        'created_by',
        'updated_by',
        'is_free',
        'is_standalone',
        'type',
        'status',
        'price',
        'image',
        'is_separately_sold',
        'order',
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'lesson_id';
    protected $table = 'lessons';
    protected $appends  = ["image_link", "session_count", "audio_count", "document_count", "exam_count", "children_count", "children_session_count", "children_audio_count", "children_document_count", "children_exam_count"];


    /* public function getImageLinkAttribute()
    {
        return $this->image ? asset($this->image) : '';
    } */
    public function imageLink(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->image ? asset('storage/' . $this->image) : '',
        );
    }

    public function children()
    {
        return $this->hasMany(Lesson::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Lesson::class, 'parent_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function video()
    {
        return $this->morphOne(Video::class, 'videoable');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'lesson_id');
    }

    public function getSessionCountAttribute()
    {
        return
            $this->contents()->where('type', ContentTypeEnum::SESSION->value)->count();
    }

    public function getChildrenSessionCountAttribute()
    {
        return     $this->children->sum(fn($child) => $child->session_count);
    }

    public function getAudioCountAttribute()
    {
        return $this->contents()->where('type', ContentTypeEnum::AUDIO->value)->count();
    }

    public function getChildrenAudioCountAttribute()
    {
        return $this->children->sum(fn($child) => $child->audio_count);
    }

    public function getDocumentCountAttribute()
    {
        return $this->contents()->where('type', ContentTypeEnum::DOCUMENT->value)->count();
    }

    public function getChildrenDocumentCountAttribute()
    {
        return $this->children->sum(fn($child) => $child->document_count);
    }

    public function getChildrenCountAttribute()
    {
        return $this->children->count();
    }

    public function getExamCountAttribute()
    {
        return $this->contents()->where('type', ContentTypeEnum::EXAM->value)->count();
    }

    public function getChildrenExamCountAttribute()
    {
        return $this->children->sum(fn($child) => $child->exam_count);
    }


    protected static function booted()
    {
        static::creating(function ($lesson) {
            $lesson->order = self::generateOrder($lesson);
        });

        static::updating(function ($lesson) {
            // Recalculate order only if relevant fields changed or order is null
            if (
                $lesson->isDirty('course_id') ||
                $lesson->isDirty('parent_id') ||
                is_null($lesson->order)
            ) {
                $lesson->order = self::generateOrder($lesson);
            }
        });
    }

    protected static function generateOrder($lesson): int
    {
        if ($lesson->parent_id) {
            return self::where('parent_id', $lesson->parent_id)->max('order') + 1;
        }

        if ($lesson->course_id) {
            return self::where('course_id', $lesson->course_id)
                ->whereNull('parent_id') // ensure it's a top-level lesson
                ->max('order') + 1;
        }

        return 1;
    }
}
