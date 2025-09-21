<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Application\Trait\ContentViewAccessTrait;
use App\Modules\Course\Infrastructure\Persistence\Models\Live\Live;
use App\Modules\Course\Infrastructure\Persistence\Models\Poll\Poll;
use App\Modules\Course\Infrastructure\Persistence\Models\Audio\Audio;
use App\Modules\Course\Infrastructure\Persistence\Models\Level\Level;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Course\Infrastructure\Persistence\Models\Session\Session;
use App\Modules\Course\Infrastructure\Persistence\Models\Document\Document;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Models\Referance\Referance;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswer\LiveAnswer;
use App\Modules\Course\Infrastructure\Persistence\Models\Session\ContentSession;
use App\Modules\Course\Infrastructure\Persistence\Models\ContentView\ContentView;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestion\LiveQuestion;

class Content extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use ContentViewAccessTrait;
    protected $fillable = [
        'parent_id',
        'organization_id',
        'created_by',
        'updated_by',
        'lesson_id',
        'course_id',
        'level_id',
        'type',
        'status',
        'image',
        'order',
        'can_skip',
        'skip_rate',
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'content_id';
    protected $table = 'contents';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function children()
    {
        return $this->hasMany(Content::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Content::class, 'parent_id');
    }

    public function video()
    {
        return $this->morphOne(Video::class, 'videoable');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function session()
    {
        return $this->hasOne(ContentSession::class, 'content_id');
    }

    public function audio()
    {
        return $this->hasOne(Audio::class, 'content_id');
    }
    public function document()
    {
        return $this->hasOne(Document::class, 'content_id');
    }

    // public function courses()
    // {
    //     return $this->hasManyThrough(
    //         Course::class,     // Final model
    //         Lesson::class,     // Intermediate model
    //         'id',              // Local key on Lesson (referenced by Content.lesson_id)
    //         'id',              // Local key on Course (referenced by Lesson.course_id)
    //         'lesson_id',       // Foreign key on Content model (content.lesson_id → lesson.id)
    //         'course_id'        // Foreign key on Lesson model (lesson.course_id → course.id)
    //     );
    // }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function referances()
    {
        return $this->morphMany(Referance::class, 'referancable');
    }

    public function poll()
    {
        return $this->hasOne(Poll::class, 'content_id');
    }

    public function live()
    {
        return $this->hasOne(Live::class, 'content_id');
    }

    public function liveQuestions()
    {
        return $this->hasOne(LiveQuestion::class, 'content_id');
    }

    public function liveAnswers()
    {
        return $this->hasMany(LiveAnswer::class, 'content_id');
    }

    protected static function booted()
    {
        static::creating(function ($content) {
            $content->order = self::generateOrder($content);
        });

        static::updating(function ($content) {
            // Optional: Only update order if course_id or lesson_id is changed or order is null
            if (
                $content->isDirty('course_id') ||
                $content->isDirty('lesson_id') ||
                is_null($content->order)
            ) {
                $content->order = self::generateOrder($content);
            }
        });
    }

    protected static function generateOrder($content)
    {
        if ($content->lesson_id) {
            return self::where('lesson_id', $content->lesson_id)->max('order') + 1;
        }

        if ($content->course_id) {
            return self::where('course_id', $content->course_id)
                ->whereNull('lesson_id') // prevent overlap with lesson-specific contents
                ->max('order') + 1;
        }

        return 1; // fallback
    }

    public function views()
    {
        return $this->hasMany(ContentView::class, 'content_id');
    }

    public function isViewed($user_id)
    {
        // dd($user_id);
        return $this->views()->where('user_id', $user_id)->exists();
    }

    public function userViewStops($user_id)
    {
        return $this->views()->where('user_id', $user_id)->where('is_finished', false)->first()?->stops ?? 0;
    }

    public function isFinished($user_id)
    {
        return $this->views()->where('user_id', $user_id)->where('is_finished', true)->exists();
    }

    public function isFree()
    {
        if ($this->lesson_id == null) {
            return $this->course->is_free;
        }
        return $this->lesson->is_free;
    }
}
