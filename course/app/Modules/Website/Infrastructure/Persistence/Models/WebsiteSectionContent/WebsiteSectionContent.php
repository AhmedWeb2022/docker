<?php

namespace App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionContent;

use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSection\WebsiteSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WebsiteSectionContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'website_section_id',
        'contentable_id',
        'contentable_type',
    ];
    public function websiteSection()
    {
        return $this->belongsTo(WebsiteSection::class);
    }
    public function contentable()
    {
        return $this->morphTo();
    }
}
