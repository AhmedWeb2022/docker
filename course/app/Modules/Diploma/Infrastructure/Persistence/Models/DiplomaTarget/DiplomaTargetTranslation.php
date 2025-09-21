<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaTarget;

use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiplomaTargetTranslation extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
    ];
    protected $table = 'diploma_target_translations';
    public $timestamps = false;
}
