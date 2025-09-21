<?php

namespace App\Modules\Course\Http\Resources\Course\Website;

use Illuminate\Http\Request;
use App\Modules\Base\Domain\Holders\AuthHolder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Http\Resources\Rate\RateResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;

use App\Modules\Course\Application\Enums\Course\CourseLevelTypeEnum;
use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;
use App\Modules\Course\Http\Resources\Lesson\Website\LessonResource;
use App\Modules\Course\Http\Resources\Certificate\CertificateResource;
use App\Modules\Course\Http\Resources\Content\Website\ContentResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;
use App\Modules\Course\Http\Resources\CoursePlatform\CoursePlatformResource;
use App\Modules\Course\Http\Resources\Lesson\Website\LessonWithChildrenResource;

class CourseDetailsForLessonResource extends JsonResource
{
    protected $lesson_id;
    public function __construct($resource, $lesson_id)
    {
        parent::__construct($resource);
        $this->lesson_id = $lesson_id;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $teachers = $this->teachers();
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        $hasSubscription = $user ? $this->subscriptions()->where('user_id', $user->id)->first() : false;
        // dd($teachers);
        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language')  !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $cardDescription = $request->header('Accept-Language')  !== "*" ? getTranslation('card_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'card_description');
        $data = [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($cardDescription) ? 'card_descriptions' : 'card_description' => $cardDescription,
            'stage' => $this->getStage(),
            // 'price' => $this->setting->price ?? 0,
            'has_discount' => $this->hasCurrentDiscount() ? true : false,
            'discount' => $this->hasCurrentDiscount() ? $this->currentDiscount()->discount_amount : 0,
            'discount_ammount' => $this->hasCurrentDiscount() ? $this->discountAmmount() : 0,
            'price_after_discount' => $this->priceAfterDiscount(),
            'payment' => $this->hasPayment() ? new CoursePaymentResource($this->coursePayment) : null,
            'image' => $this->image_link,
            'is_percentage' => $this->setting->is_percentage ?? false,
            "level_type" => $this->level_type,
            'discount' => $this->setting->discount ?? 0,
            'average_rating' => round($this->rates()->avg('rate') ?? 0, 2),
            'reviews_count' => $this->rates()->count() ?? 0,
            "ratings" => RateResource::collection($this->rates ?? []),
            'total_duration' => $this->total_duration ?? 0,
            'has_favorites' => $this->has_favorites($user?->id) ?? false,
            'is_subscribed' => $hasSubscription ? true : false,
            'subscription_status' => $hasSubscription ? $hasSubscription->status : null,
            "has_certificate" => $this->certificate ? true : false,
            'number_of_stages' => $this->level_type ?? 0,
            'number_of_lectures' => $this->lessons()->count() ?? 0,
            'number_of_quiezzes' => $this->contents()->where('type', ContentTypeEnum::EXAM->value)->count() ?? 0,
            'number_of_hours' => $this->total_duration ?? 0,
            'number_of_pdfs' => $this->contents()->where('type', ContentTypeEnum::DOCUMENT->value)->count() ?? 0,
            'number_of_sessions' => $this->contents()->where('type', ContentTypeEnum::SESSION->value)->count() ?? 0,
            'number_of_audios' => $this->contents()->where('type', ContentTypeEnum::AUDIO->value)->count() ?? 0,
            'number_of_live' => $this->contents()->where('type', ContentTypeEnum::LIVE->value)->count() ?? 0,
            'telegram_link' => $this->setting->telegram_link ?? null,
            'whatsapp_link' => $this->setting->whatsapp_link ?? null,
            'certificate' => new CertificateResource($this->certificate),
            "video" => new VideoResource($this->video ?? null),
            // 'setting' => new SettingResource($this->setting ?? null),
            'platforms' => CoursePlatformResource::collection($this->coursePlatforms ?? []),
            "similar_courses" => CourseResource::collection($this->similar_courses() ?? []),

        ];
        // dd($this->level_type);
        if ($this->level_type == CourseLevelTypeEnum::HAS_ONLY_CONTENT->value) {
            $data['course_content'] = ContentResource::collection($this->contents);
        } elseif ($this->level_type == CourseLevelTypeEnum::HAS_LEVEL_AND_CONTENT->value) {
            $data['course_lessons'] = new  LessonResource($this->lessons()->where('id', $this->lesson_id)->first());
        } elseif ($this->level_type == CourseLevelTypeEnum::HAS_UNIT_AND_LEVEL_AND_CONTENT->value) {
            // dd($this->lessons);
            $data['units'] = new LessonWithChildrenResource($this->lessons()->whereNull('parent_id')->where('id', $this->lesson_id)->first());
        }
        $data['teachers'] = $teachers ? $teachers : [];
        return $data;
    }
}
