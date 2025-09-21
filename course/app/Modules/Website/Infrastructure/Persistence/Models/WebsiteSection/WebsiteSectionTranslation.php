<?php

namespace App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSection;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebsiteSectionTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'website_sections_translations';
    protected $fillable = [
        'website_section_id',
        'locale',
        'title',
        'sub_title',
        'description',
    ];
}
