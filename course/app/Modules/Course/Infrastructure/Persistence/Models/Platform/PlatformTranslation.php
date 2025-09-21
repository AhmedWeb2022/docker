<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Platform;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'platform_translations';
    protected $fillable = [
        'platform_id',
        'locale',
        'title',
        'description',
    ];
}
