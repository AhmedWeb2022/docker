<?php

namespace App\Modules\Course\Http\Resources\Partner\Api;

use App\Modules\Course\Http\Resources\Certificate\Website\CertificateResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerCourseResource extends JsonResource
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
        return [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($description) ? 'descriptions' : 'description' => $description,
            'image' => $this->image_link,
            'cover' => $this->cover_link,
            'link' => $this->link,
            'is_website' => $this->is_website,
            'courses' => CourseResource::collection($this->courses),
        ];
    }
}
