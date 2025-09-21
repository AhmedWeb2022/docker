<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Level;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LevelTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'level_translations';
    protected $fillable = [
        'level_id',
        'locale',
        'title',
    ];
}
