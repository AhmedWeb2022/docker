<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\PollAnswer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollAnswerTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'poll_answer_translations';
    protected $fillable = [
        'poll_answer_id',
        'locale',
        'title',
    ];
}
