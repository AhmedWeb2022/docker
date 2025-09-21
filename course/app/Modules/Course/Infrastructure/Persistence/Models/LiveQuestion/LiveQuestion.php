<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestion;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswer\LiveAnswer;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestionAttachment\LiveQuestionAttachment;

class LiveQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'content_id',
        'parent_id',
        'question_type',
        'identicality',
        'identicality_percentage',
        'difficulty',
        'difficulty_level',
        'question',
        'degree',
        'time',
        'creator',
        'image',
    ];

    protected $table = 'live_questions';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function answers()
    {
        return $this->hasMany(LiveAnswer::class, 'live_question_id');
    }

    public function attachments()
    {
        return $this->hasMany(LiveQuestionAttachment::class, 'live_question_id');
    }
}
