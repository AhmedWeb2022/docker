<?php

namespace App\Modules\Course\Http\Resources\Course;


use Illuminate\Http\Request;

use App\Modules\Base\Domain\Holders\AuthHolder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use App\Modules\Course\Application\Enums\Content\ContentTypeEnum;
use App\Modules\Course\Http\Resources\CourseSetting\SettingResource;
use App\Modules\Course\Http\Resources\CoursePayment\CoursePaymentResource;
use App\Modules\Course\Http\Resources\Certificate\Website\CertificateResource;

class CourseWebsiteApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->teachers() );
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);
        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $description = $request->header('Accept-Language')  !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');
        $cardDescription = $request->header('Accept-Language')  !== "*" ? getTranslation('card_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'card_description');
        return [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            is_array($cardDescription) ? 'card_descriptions' : 'card_description' => $cardDescription,
            'image' => $this->image_link,
            'is_free' => $this->is_free,
            'has_discount' => $this->hasCurrentDiscount() ? true : false,
            'discount' => $this->hasCurrentDiscount() ? $this->currentDiscount()->discount_amount . '%' : 0 . '%',
            'discount_ammount' => $this->hasCurrentDiscount() ? $this->discountAmmount() : 0,
            'price_after_discount' => $this->priceAfterDiscount(),
            'payment' => $this->hasPayment() ? new CoursePaymentResource($this->coursePayment) : null,
            'rating' => $this->rates->avg('rate') ?? 0,
            'reviews_count' => $this->rates()->count() ?? 0,
            'total_duration' => $this->total_duration,
            'is_favorite' => $this->has_favorites($user?->id) ?? false,
            'teachers' => $this->teachers() ?? [],
            'buy_date' => $user ? $this->whereHas('subscriptions', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first()?->created_at->format('Y-m-d') : false,
            'is_certificate' => $this->certificate ? 1 : 0,
            'certificate' => new CertificateResource($this->certificate),
            'number_of_lectures' => $this->lessons()->count() ?? 0,
            'number_of_quiezzes' => $this->contents()->where('type', ContentTypeEnum::EXAM->value)->count() ?? 0,
            'number_of_pdfs' => $this->contents()->where('type', ContentTypeEnum::DOCUMENT->value)->count() ?? 0,
            'number_of_sessions' => $this->contents()->where('type', ContentTypeEnum::SESSION->value)->count() ?? 0,
            'number_of_audios' => $this->contents()->where('type', ContentTypeEnum::AUDIO->value)->count() ?? 0,
        ];
    }
}
