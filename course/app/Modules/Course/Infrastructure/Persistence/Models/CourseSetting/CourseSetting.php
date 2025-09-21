<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\CourseSetting;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class CourseSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'course_id',
        'code_status',
        'is_security',
        'is_watermark',
        'is_voice',
        'is_emulator',
        'time_number',
        'number_of_voice',
        'watch_video',
        'number_watch_video',
        'price',
    ];
    protected $table = 'course_settings';



}
