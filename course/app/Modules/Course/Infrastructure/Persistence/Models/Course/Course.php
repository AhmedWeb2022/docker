<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Course;



use App\Modules\Course\Infrastructure\Persistence\Models\CourseOffer\CourseOffer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Random\RandomError;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\Course\Infrastructure\Persistence\Models\Rate\Rate;
use App\Modules\Course\Infrastructure\Persistence\Models\Level\Level;

use App\Modules\Course\Infrastructure\Persistence\Models\Video\Video;

use App\Modules\Course\Infrastructure\Persistence\Models\Lesson\Lesson;
use App\Modules\Course\Infrastructure\Persistence\Models\Content\Content;
use App\Modules\Course\Infrastructure\Persistence\Models\Partner\Partner;
use App\Modules\Diploma\Infrastructure\Persistence\Models\Diploma\Diploma;
use App\Modules\Course\Application\Enums\Subscription\SubscriptionTypeEnum;
use App\Modules\Course\Infrastructure\Persistence\Models\Favorite\Favorite;
use App\Modules\Course\Infrastructure\Persistence\Models\Platform\Platform;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Course\Infrastructure\Persistence\Entities\Course\CourseEntity;
use App\Modules\Course\Infrastructure\Persistence\Models\Certificate\Certificate;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseLevel\CourseLevel;
use App\Modules\Course\Infrastructure\Persistence\ApiService\Stage\StageApiService;
use App\Modules\Course\Infrastructure\Persistence\Models\Subscription\Subscription;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseSetting\CourseSetting;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseTeacher\CourseTeacher;
use App\Modules\Course\Infrastructure\Persistence\Repositories\Course\CourseRepository;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseDependency\CoursePayment;
use App\Modules\Diploma\Infrastructure\Persistence\Models\DiplomaContent\DiplomaContent;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseDependency\CoursePlatform;
use App\Modules\Course\Infrastructure\Persistence\Models\CourseSubjectStage\CourseSubjectStage;
use App\Modules\Website\Infrastructure\Persistence\Models\WebsiteSectionContent\WebsiteSectionContent;

class Course extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'parent_id',
        'organization_id',
        'stage_id',
        'subject_id',
        'certificate_id',
        'is_certificate',
        'partner_id',
        'created_by',
        'updated_by',
        'type',
        'status',
        'is_private',
        'has_website',
        'has_app',
        'start_date',
        'end_date',
        'image',
        'level_type',
        'has_favourite',
        'has_hidden',
        'has_discount',
        'is_free'
    ];
    public $translatedAttributes = ['title', 'description', 'card_description'];
    protected $translationForeignKey = 'course_id';
    protected $table = 'courses';
    protected $appends = ["image_link"];


    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }
    public function websiteSectionContents()
    {
        return $this->morphMany(WebsiteSectionContent::class, 'contentable');
    }
    public function children()
    {
        return $this->hasMany(Course::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Course::class, 'parent_id');
    }

    public function video()
    {
        return $this->hasOne(Video::class, 'videoable_id')->where('videoable_type', Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    public function setting(): HasOne
    {
        return $this->hasOne(CourseSetting::class, 'course_id');
    }

    public function getStage()
    {
        $courseRepository = new CourseRepository();
        $stage = $courseRepository->getStage($this->id);
        // dd($response);
        if (!$stage) {
            return null;
        }
        return $stage;
    }

    public function getSubject()
    {
        $courseRepository = new CourseRepository();
        $subject = $courseRepository->getSubject($this->id);
        // dd($response);
        if (!$subject) {
            return null;
        }
        return $subject;
    }

    public function coursePayment()
    {
        return $this->hasOne(CoursePayment::class, 'course_id');
    }

    public function hasPayment()
    {
        return $this->coursePayment()->exists();
    }
    public function isPaid()
    {
        return $this->hasPayment() ? $this->coursePayment()->where('is_paid', 1)->exists() : 0;
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'course_platforms', 'course_id', 'platform_id');
    }

    public function coursePlatforms()
    {
        return $this->hasMany(CoursePlatform::class, 'course_id');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'course_levels', 'course_id', 'level_id');
    }

    public function CourseLevels()
    {
        return $this->hasMany(CourseLevel::class, 'course_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'course_id');
    }

    public function courseTeachers()
    {
        return $this->hasMany(CourseTeacher::class, 'course_id');
    }
    public function teachers()
    {
        $courseRepository = new CourseRepository();
        return $courseRepository->getTeachers($this->id);
    }

    public function rates()
    {
        return $this->morphMany(Rate::class, 'rateable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
    public function has_favorites($user_id)
    {
        return $this->morphMany(Favorite::class, 'favoritable')->where('user_id', $user_id)->exists();
    }
    public function similar_courses()
    {
        return Course::where('stage_id', $this->stage_id)->where('id', '<>', $this->id)->get();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'type_id')->where('type', SubscriptionTypeEnum::COURSE->value);
    }

    public function diplomaContents()
    {
        return $this->belongsToMany(DiplomaContent::class, 'diploma_content_courses', 'course_id', 'diploma_content_id');
    }

    public function diplomas()
    {
        return $this->belongsToMany(Diploma::class, 'diploma_content_courses', 'course_id', 'diploma_id');
    }

    public function courseEntity(): CourseEntity
    {
        return CourseEntity::fromCourse($this); // ✅ will now preserve relationships
    }

    public function courseSubjectStages()
    {
        return $this->hasMany(CourseSubjectStage::class, 'course_id');
    }

    public function subjectStages()
    {
        $stageApiService = new StageApiService();
        $stageSubjectIds = $this->courseSubjectStages()->pluck('subject_stage_id')->toArray();
        return $stageApiService->fetchSubjectStages($stageSubjectIds)['data'];
    }

    public function offers(): HasMany
    {
        return $this->hasMany(CourseOffer::class, 'course_id');
    }

    public function currentDiscount()
    {
        return $this->offers()->where('discount_from_date', '<=', now())->where('discount_to_date', '>=', now())->first();
    }

    public function hasCurrentDiscount()
    {
        return $this->offers()->where('discount_from_date', '<=', now())->where('discount_to_date', '>=', now())->exists();
    }
    public function discountAmmount()
    {
        if ($this->hasCurrentDiscount()) {
            $price = $this->hasPayment() ? $this->coursePayment->price : 0;
            $discount = $this->hasCurrentDiscount() ? $this->currentDiscount()->discount_amount : 0;
            return ($price * $discount) / 100;
        }
        return 0;
    }
    public function priceAfterDiscount()
    {
        if ($this->hasCurrentDiscount()) {
            $price = $this->hasPayment() ? $this->coursePayment->price : 0;
            $discount = $this->hasCurrentDiscount() ? $this->currentDiscount()?->discount_amount : 0;
            // return percentage discount the discount is in %
            return $price - $this->discountAmmount();
        }
        return $this->hasPayment() ? $this->coursePayment->price : 0;
    }
}
