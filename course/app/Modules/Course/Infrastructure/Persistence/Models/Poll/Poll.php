<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Poll;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Models\PollAnswer\PollAnswer;

class Poll extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'parent_id',
        'organization_id',
        'content_id',
        'is_fake',
        'image',
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'poll_id';
    protected $table = 'polls';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }



    public function children()
    {
        return $this->hasMany(Poll::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Poll::class, 'parent_id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function pollAnswers()
    {
        return $this->hasMany(PollAnswer::class, 'poll_id');
    }
}
