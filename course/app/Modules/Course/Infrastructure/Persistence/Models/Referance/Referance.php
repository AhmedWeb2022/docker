<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Referance;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Referance extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'image',
        'referancable_id',
        'referancable_type',
        'link',
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'referance_id';
    protected $table = 'referances';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function referancable()
    {
        return $this->morphTo();
    }
}
