<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Live;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Group\Group;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Live\LiveRepository;

class Live extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'content_id',
        'group_id',
        'course_id',
        'teacher_id',
        'status',
        'image',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];
    public $translatedAttributes = ['title', 'description'];
    protected $translationForeignKey = 'live_id';
    protected $table = 'lives';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function teacher()
    {
        $liveRepository = new LiveRepository();
        return $liveRepository->getTeachers($this->id);
    }

    public function getDurationAttribute()
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $duration = $end - $start;

        return gmdate("H:i:s", $duration);
    }
    public function getRemainingTimeAttribute()
    {
        $currentTime = now();

        $startDateTime = \Carbon\Carbon::parse("{$this->start_date} {$this->start_time}");
        $endDateTime = \Carbon\Carbon::parse("{$this->end_date} {$this->end_time}");

        if ($currentTime->lt($startDateTime)) {
            return $startDateTime->diff($currentTime)->format('%H:%I:%S'); // Time until it starts
        } elseif ($currentTime->gt($endDateTime)) {
            return '00:00:00'; // Already ended
        } else {
            return $endDateTime->diff($currentTime)->format('%H:%I:%S'); // Time until it ends
        }
    }

    public function getIsEndAttribute()
    {
        $currentTime = now();
        $endDateTime = \Carbon\Carbon::parse("{$this->end_date} {$this->end_time}");
        return $currentTime->gt($endDateTime);
    }
}
