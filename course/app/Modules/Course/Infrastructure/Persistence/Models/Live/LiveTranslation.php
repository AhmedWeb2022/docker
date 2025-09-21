<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Live;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiveTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'live_translations';
    protected $fillable = [
        'live_id',
        'locale',
        'title',
        'description',
    ];
}
