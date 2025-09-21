<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Review;

use App\Models\User;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Review\ReviewRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'content_id',
        'follow_up',
        'degree_focus',
        'interacting_tasks',
        'behavior_cooperation',
        'progress_understanding',
        'notes',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function user()
    {
        $courseRepository = new ReviewRepository();
        return $courseRepository->getUser($this->user_id);
    }
}
