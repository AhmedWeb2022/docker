<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Referance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferanceTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'referance_translations';
    protected $fillable = [
        'referance_id',
        'locale',
        'title',
        'description',
    ];
}
