<?php

namespace App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaAbout;

use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiplomaAboutTranslation extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'description',
    ];
    protected $table = 'diploma_about_translations';
    public $timestamps = false;
}
