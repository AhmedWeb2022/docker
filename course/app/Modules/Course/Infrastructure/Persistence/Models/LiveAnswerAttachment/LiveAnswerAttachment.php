<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswerAttachment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\LiveAnswer\LiveAnswer;

class LiveAnswerAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'live_answer_id',
        'media',
        'type',
        'alt',
    ];

    protected $table = 'live_answer_attachments';
    protected $appends  = ["image_link"];


    public function getMediaLinkAttribute()
    {
        return $this->media ? asset('storage/' . $this->media) : '';
    }

    public function liveAnswer()
    {
        return $this->belongsTo(LiveAnswer::class, 'live_answer_id');
    }
}
