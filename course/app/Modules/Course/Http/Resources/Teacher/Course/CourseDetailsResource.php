<?php

namespace App\Modules\Course\Http\Resources\Teacher\Course;

use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use App\Modules\Course\Http\Resources\Certificate\CertificateResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Rate\RateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;

use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Application\Enums\Course\CourseLevelTypeEnum;
use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;
use App\Modules\Course\Http\Resources\Lesson\Website\LessonResource;
use App\Modules\Course\Http\Resources\Content\Website\ContentResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;
use App\Modules\Course\Http\Resources\Lesson\Website\LessonWithChildrenResource;

class CourseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $teachers = $this->teachers();
        // dd($teachers);
        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language')  !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $cardDescription = $request->header('Accept-Language')  !== "*" ? getTranslation('card_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'card_description');
        $data = [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($cardDescription) ? 'card_descriptions' : 'card_description' => $cardDescription,
            'price' => $this->setting->price ?? 120,
            'image' => $this->image_link,
            'is_percentage' => $this->setting->is_percentage ?? false,
            "level_type" => $this->level_type,
            'discount' => $this->setting->discount ?? 0,
            'average_rating' => round($this->rates()->avg('rate') ?? 0, 2),
            'reviews_count' => $this->rates()->count() ?? 0,
            "ratings" => RateResource::collection($this->rates ?? []),
            'total_duration' => $this->total_duration ?? 0,
            "has_certificate" => $this->certificate ? true : false,
            'number_of_stages' => $this->level_type ?? 0,
            'number_of_lectures' => $this->lessons()->count() ?? 0,
            'number_of_quiezzes' => $this->contents()->where('type', ContentTypeEnum::EXAM->value)->count() ?? 0,
            'number_of_hours' => $this->total_duration ?? 0,
            'number_of_pdfs' => $this->contents()->where('type', ContentTypeEnum::DOCUMENT->value)->count() ?? 0,
            'number_of_sessions' => $this->contents()->where('type', ContentTypeEnum::SESSION->value)->count() ?? 0,
            'number_of_audios' => $this->contents()->where('type', ContentTypeEnum::AUDIO->value)->count() ?? 0,
            'number_of_live' => 0,
            'telegram_link' => $this->setting->telegram_link ?? null,
            'whatsapp_link' => $this->setting->whatsapp_link ?? null,
            'certificate' => new CertificateResource($this->certificate),
            "video" => new VideoResource($this->video ?? null),
            "similar_courses" => CourseResource::collection($this->similar_courses() ?? []),

        ];
        // dd($this->level_type);
        if ($this->level_type == CourseLevelTypeEnum::HAS_ONLY_CONTENT->value) {
            $data['course_content'] = ContentResource::collection($this->contents);
        } elseif ($this->level_type == CourseLevelTypeEnum::HAS_LEVEL_AND_CONTENT->value) {
            $data['course_lessons'] = LessonResource::collection($this->lessons);
        } elseif ($this->level_type == CourseLevelTypeEnum::HAS_UNIT_AND_LEVEL_AND_CONTENT->value) {
            // dd($this->lessons);
            $data['units'] = LessonWithChildrenResource::collection($this->lessons()->whereNull('parent_id')->get());
        }
        $data['teachers'] = $teachers ? $teachers : [];
        return $data;
    }
}
