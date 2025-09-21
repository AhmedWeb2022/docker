<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\Faq;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqTranslation extends Model
{
    use HasFactory;
    protected $table = 'faq_translations';
    protected $fillable = ['question',"answer"];
}
