<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'content_translations';
    protected $fillable = [
        'content_id',
        'locale',
        'title',
    ];
}
