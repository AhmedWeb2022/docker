<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestionAttachment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveQuestion\LiveQuestion;

class LiveQuestionAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'live_question_id',
        'media',
        'type',
        'alt',
    ];

    protected $table = 'live_question_attachments';
    protected $appends  = ["image_link"];


    public function getMediaLinkAttribute()
    {
        return $this->media ? asset('storage/' . $this->media) : '';
    }

    public function liveQuestion()
    {
        return $this->belongsTo(LiveQuestion::class, 'live_question_id');
    }
}
