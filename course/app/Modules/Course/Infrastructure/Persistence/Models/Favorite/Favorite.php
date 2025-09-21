<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Favorite;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';

    protected $fillable = [
        'user_id',
        'favoritable_id',
        'favoritable_type',
    ];

    public function favoritable()
    {
        return $this->morphTo();
    }
}
