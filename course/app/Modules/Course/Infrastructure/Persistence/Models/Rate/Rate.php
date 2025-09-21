<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Rate;

use App\Models\User;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Rate\RateRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    protected $table = 'rates';

    protected $fillable = [
        'user_id',
        'rateable_id',
        'rateable_type',
        'rate',
        'comment',
    ];

    public function rateable()
    {
        return $this->morphTo();
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function users()
    {
        $courseRepository = new RateRepository();
        return $courseRepository->getUser($this->user_id);
    }

}
