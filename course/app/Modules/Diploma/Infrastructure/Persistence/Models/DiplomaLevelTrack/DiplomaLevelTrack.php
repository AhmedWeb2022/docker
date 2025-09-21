<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevelTrack;

use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContent\DiplomaContent;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel\DiplomaLevel;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiplomaLevelTrack extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = ['title', 'description',];

    protected $table = 'diploma_level_tracks';
    protected $fillable = ['diploma_level_id', 'diploma_id'];
            public function Diploma()
    {
        return $this->belongsTo(Diploma::class,  'diploma_id');
    }

    public function diplomaLevel()
    {
        return $this->belongsTo(DiplomaLevel::class, 'diploma_level_id');
    }

    public function contents()
    {
        return $this->hasMany(DiplomaContent::class, 'diploma_level_track_id');
    }
}
