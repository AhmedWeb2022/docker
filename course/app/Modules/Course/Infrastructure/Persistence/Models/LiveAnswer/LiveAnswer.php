<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswer;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Group\Group;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestion\LiveQuestion;
use App\Modules\Course\Infrastructure\Persistence\Repositories\LiveAnswer\LiveAnswerRepository;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswerAttachment\LiveAnswerAttachment;

class LiveAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'content_id',
        'live_question_id',
        'answer',
        'is_correct',
        'image',
    ];

    protected $table = 'live_answers';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function question()
    {
        return $this->belongsTo(LiveQuestion::class, 'live_question_id');
    }

    public function attachments()
    {
        return $this->hasMany(LiveAnswerAttachment::class, 'live_answer_id');
    }

}
