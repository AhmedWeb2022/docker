<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Audio;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AudioTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'audio_translations';
    protected $fillable = [
        'audio_id',
        'locale',
        'title',
        'description',
    ];
}
