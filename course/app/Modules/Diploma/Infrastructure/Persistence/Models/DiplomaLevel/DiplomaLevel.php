<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel;

use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContent\DiplomaContent;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevelTrack\DiplomaLevelTrack;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiplomaLevel extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = ['title', 'description'];

    protected $table = 'diploma_levels';
    protected $fillable = [
        'diploma_id',
        'image',
        'has_track',
    ];

    public function imageLink(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset('storage/' . $this->image) : "",
            // set: fn($value) => $value ? 'diplomas/' . $value : null
        );
    }

        public function Diploma()
    {
        return $this->belongsTo(Diploma::class,  'diploma_id');
    }

    public function tracks()
    {
        return $this->hasMany(DiplomaLevelTrack::class, 'diploma_level_id');
    }

    public function contents()
    {
        return $this->hasMany(DiplomaContent::class, 'diploma_level_id');
    }
}
