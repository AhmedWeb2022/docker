<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'document_translations';
    protected $fillable = [
        'document_id',
        'locale',
        'title',
        'description',
    ];
}
