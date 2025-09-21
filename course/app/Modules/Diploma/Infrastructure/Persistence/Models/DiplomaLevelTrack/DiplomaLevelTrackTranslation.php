<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevelTrack;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiplomaLevelTrackTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'diploma_level_track_translations';
    protected $fillable = ['title', 'description',];
}
