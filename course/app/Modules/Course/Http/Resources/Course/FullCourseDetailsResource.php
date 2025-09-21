<?php

namespace App\Modules\Course\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use App\Modules\Course\Http\Resources\Lesson\LessonResource;
use App\Modules\Course\Http\Resources\Partner\PartnerResource;
use App\Modules\Course\Http\Resources\Platform\PlatformResource;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;
use App\Modules\Course\Http\Resources\Certificate\CertificateResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;
use App\Modules\Course\Http\Resources\CoursePlatform\CoursePlatformResource;

class FullCourseDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language')  !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $cardDescription = $request->header('Accept-Language')  !== "*" ? getTranslation('card_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'card_description');
        return [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($cardDescription) ? 'card_descriptions' : 'card_description' => $cardDescription,
            'stage' => $this->getStage(),
            'subject' => $this->getSubject(),
            'teachers' => $this->teachers() ?? [],

            'type' => $this->type,
            'status' => $this->status,
            'is_private' => $this->is_private,
            'has_website' => $this->has_website,
            'level_type' => $this->level_type,
            'has_app' => $this->has_app,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image' => $this->image_link,
            'number_of_stages' => $this->level_type ?? 0,
            'number_of_lectures' => $this->lessons()->count() ?? 0,
            'number_of_quiezzes' => $this->contents()->where('type', ContentTypeEnum::EXAM->value)->count() ?? 0,
            'number_of_hours' => $this->total_duration ?? 0,
            'number_of_pdfs' => $this->contents()->where('type', ContentTypeEnum::DOCUMENT->value)->count() ?? 0,
            'number_of_sessions' => $this->contents()->where('type', ContentTypeEnum::SESSION->value)->count() ?? 0,
            'number_of_audios' => $this->contents()->where('type', ContentTypeEnum::AUDIO->value)->count() ?? 0,
            'number_of_live' => $this->contents()->where('type', ContentTypeEnum::LIVE->value)->count() ?? 0,
            'contain_live' => $this->contain_live,
            'is_certificate' => $this->certificate ? 1 : 0,
            'education_type' => $this->education_type,
            'content_count' => $this->contents()->count() ? $this->contents()->count() : 0,
            'lesson_count' => $this->lessons()->count() ? $this->lessons()->count() : 0,
            'is_paid' => $this->isPaid() ? 1 : 0,
            "students_count" => $this->subscriptions()->count() ?? 0,
            'payment' => $this->hasPayment() ? new CoursePaymentResource($this->coursePayment) : null,
            'video' => new VideoResource($this->video),
            'partner' => new PartnerResource($this->partner),
            'certificate' => new CertificateResource($this->certificate),
            'setting' => new SettingResource($this->setting ?? null),
            'platforms' => PlatformResource::collection($this->platforms),
            'lessons' => LessonResource::collection($this->lessons),
        ];
    }
}
