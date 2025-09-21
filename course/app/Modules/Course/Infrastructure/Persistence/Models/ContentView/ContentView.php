<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\ContentView;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class ContentView extends Model
{
    use HasFactory;
    protected $fillable = [
        'content_id',
        'user_id',
        'stops',
        'is_finished',
    ];
    protected $table = 'content_views';
}
