<?php

namespace App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSection;

use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionAttachment\WebsiteSectionAttachment;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionContent\WebsiteSectionContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebsiteSection extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = ['title', 'sub_title', 'description'];

    protected $table = 'website_sections';

    protected $translationForeignKey = 'website_section_id';

    protected $fillable = [
        'order',
        'image',
        'parent_id',
        'type',
        'status',
        'style',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function contents()
{
    return $this->hasMany(WebsiteSectionContent::class, 'website_section_id');
}

public function attachments()
{
    return $this->hasMany(WebsiteSectionAttachment::class);
}
public function parent()
{
    return $this->belongsTo(WebsiteSection::class, 'parent_id');
}
public function children()
{
    return $this->hasMany(WebsiteSection::class, 'parent_id');
}
public function courses()
{
    return $this->contents()->where('contentable_type', Course::class);
}
public function diplomas()
{
    return $this->contents()->where('contentable_type', Diploma::class);
}
}
