<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiplomaTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'diploma_translations';
    protected $fillable = ['title', 'short_description', 'full_description',];
}
