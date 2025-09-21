<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\PollAnswer;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Poll\Poll;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class PollAnswer extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'poll_id',
        'percentage',
        'image',
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'poll_answer_id';
    protected $table = 'poll_answers';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }


    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
    }
}
