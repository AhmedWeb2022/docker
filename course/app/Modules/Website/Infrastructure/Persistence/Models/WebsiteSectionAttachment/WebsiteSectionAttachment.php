<?php

namespace App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionAttachment;

use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSection\WebsiteSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class WebsiteSectionAttachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'website_section_id',
        'file',
        'link',
        'alt',
    ];
    public function websiteSection()
    {
        return $this->belongsTo(WebsiteSection::class);
    }
}
