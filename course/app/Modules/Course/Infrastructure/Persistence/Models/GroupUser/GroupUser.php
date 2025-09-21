<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\GroupUser;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Course\Infrastructure\Persistence\Models\Level\Level;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;

class GroupUser extends Model
{
    protected $table = 'group_users';
    protected $fillable = [
        'group_id',
        'user_id',
    ];
}
