<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Group;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'group_translations';
    protected $fillable = [
        'group_id',
        'locale',
        'title',
    ];
}
