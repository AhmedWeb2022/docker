<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaAbout;

use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class DiplomaAbout extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = [
        'title',
    ];
    protected $table = 'diploma_abouts';
    protected $fillable = [
        'diploma_id',
        'about',
    ];

    public function diploma()
    {
        return $this->belongsTo(Diploma::class, 'diploma_id');
    }
}
