<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\CourseDependency;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use App\Modules\Course\Infrastructure\Persistence\Models\Currency\Currency;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class CoursePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'is_paid',
        'price',
        'currency_id',
    ];

    protected $table = 'course_payments';


    /*public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function getCoverLinkAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : '';
    }*/

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
