<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Session;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentSessionTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'content_session_translations';
    protected $fillable = [
        'session_id',
        'locale',
        'title',
        'description',
    ];
}
