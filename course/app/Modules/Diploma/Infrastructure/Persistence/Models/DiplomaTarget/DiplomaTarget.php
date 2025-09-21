<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaTarget;

use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class DiplomaTarget extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;
    protected $table = 'diploma_targets';
    protected $fillable = [
        'diploma_id',
        'is_active',
    ];
    public $translatedAttributes = [
        'title',
    ];
    public function diploma()
    {
        return $this->belongsTo(Diploma::class, 'diploma_id');
    }
}
