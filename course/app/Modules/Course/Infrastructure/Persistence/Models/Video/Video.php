<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Video;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Video extends Model
{
    protected $fillable = [
        'title',
        'path',
        'file',
        'is_file',
        'videoable_id',
        'videoable_type',
        'video_type',
    ];

    protected $table = 'videos';
    protected $appends  = ["video_link"];


    public function getVideoLinkAttribute()
    {
        return $this->file ? asset("storage/" . $this->file) : '';
    }

    public function videoable()
    {
        return $this->morphTo();
    }
}
