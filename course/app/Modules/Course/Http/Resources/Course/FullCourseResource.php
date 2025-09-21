<?php

namespace App\Modules\Course\Http\Resources\Course;

use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use App\Modules\Course\Http\Resources\Lesson\LessonResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;

class FullCourseResource extends JsonResource
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
            'stage_subjects' => $this->subjectStages(),
            'partner_id' => $this->partner_id,
            'certificate_id' => $this->certificate_id,
            'type' => $this->type,
            'status' => $this->status,
            'is_private' => $this->is_private,
            'has_website' => $this->has_website,
            'level_type' => $this->level_type,
            'has_app' => $this->has_app,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image' => $this->image_link,
            'has_hidden' => $this->has_hidden,
            'has_favourite' => $this->has_favourite,
            // 'is_paid' => $this->isPaid() ? 1 : 0,
            // 'payment' => $this->hasPayment() ? new CoursePaymentResource($this->coursePayment) : null,
            'video' => new VideoResource($this->video),
            'lessons' => LessonResource::collection($this->lessons),
            'setting' => new SettingResource($this->setting ?? null),
        ];
    }
}
