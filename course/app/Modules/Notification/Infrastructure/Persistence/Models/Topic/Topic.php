<?php

namespace App\Modules\Notification\Infrastructure\Persistence\Models\Topic;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'topics';

    protected $fillable = [
        'name',
        'type',
        'max',
        'count',
    ];
}
