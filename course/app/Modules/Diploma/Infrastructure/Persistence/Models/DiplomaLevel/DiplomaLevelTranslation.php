<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaLevel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiplomaLevelTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'diploma_level_translations';
    protected $fillable = [
        'title',
        'description',
    ];
}
