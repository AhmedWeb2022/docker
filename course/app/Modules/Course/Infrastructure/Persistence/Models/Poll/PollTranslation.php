<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Poll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PollTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'poll_translations';
    protected $fillable = [
        'poll_id',
        'locale',
        'title',
    ];
}
