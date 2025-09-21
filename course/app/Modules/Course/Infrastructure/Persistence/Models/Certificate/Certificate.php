<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Certificate;

use App\Modules\Course\Infrastructure\Persistence\Models\Partner\Partner;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'image',
        'cover',
        'link',
        'partner_id',
        'is_website',
        'cerifictable_id',
        'cerifictable_type'
    ];
    public $translatedAttributes = ['title', 'about', 'requirements', 'target_audience', 'benefits'];
    protected $translationForeignKey = 'certificate_id';
    protected $table = 'certificates';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
