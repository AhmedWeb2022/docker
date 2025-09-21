<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Group;

use App\Modules\Course\Infrastructure\Persistence\ApiService\Auth\UserApiService;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;
use App\Modules\Course\Infrastructure\Persistence\Models\Course\Course;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Models\GroupUser\GroupUser;
use App\Modules\Course\Infrastructure\Persistence\Models\Certificate\Certificate;

class Group extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'image',
        'course_id',
        'start_date',
        'end_date',
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'group_id';
    protected $table = 'groups';
    protected $appends  = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function groupUsers()
    {
        return $this->hasMany(GroupUser::class, 'group_id');
    }

    public function users()
    {
        $userApiService = new UserApiService();
        $response = $userApiService->getUsers($this->groupUsers->pluck('user_id')->toArray());
        return $response;
    }
}
