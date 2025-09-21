<?php

namespace App\Modules\Course\Http\Resources\Course;


use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;

class CourseResource extends JsonResource
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
            'teachers' => $this->teachers() ?? [],
            'partner_id' => $this->partner_id,
            'certificate_id' => $this->certificate_id,
            'type' => $this->type,
            'status' => $this->status,
            'is_private' => $this->is_private,
            'has_website' => $this->has_website,
            'level_type' => $this->level_type,
            'has_app' => $this->has_app,
            'has_hidden' => $this->has_hidden,
            'has_favourite' => $this->has_favourite,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image' => $this->image_link,
            'content_count' => $this->contents_count,
            'lesson_count' => $this->lessons_count,
            'is_paid' => $this->isPaid() ? 1 : 0,
            // 'price' => $this->setting->price ?? 0,
            'has_discount' => $this->hasCurrentDiscount() ? true : false,
            'discount' => $this->hasCurrentDiscount() ? $this->currentDiscount()?->discount_amount . '%' : 0 . '%',
            'discount_ammount' => $this->hasCurrentDiscount() ? $this->discountAmmount() : 0,
            'price_after_discount' => $this->priceAfterDiscount(),
            'payment' => $this->hasPayment() ? new CoursePaymentResource($this->coursePayment) : null,
            'has_certificate' => $this->is_certificate ? true : false,
            'average_rating' => round($this->rates()->avg('rate') ?? 0, 2),
            'reviews_count' => $this->rates()->count() ?? 0,
            'video' => new VideoResource($this->video),
            'setting' => new SettingResource($this->setting ?? null),
            // 'payment' => $this->hasPayment() ? new CoursePaymentResource($this->coursePayment) : null,
        ];
    }
}
