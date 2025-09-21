<?php

namespace App\Modules\Diploma\Http\Resources\Level;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiplomaLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = $request->header('Accept-Language') !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $courses_count = $this->contents->map(function ($content) use ($request) {
            return $content->courses;
        })->flatten()->count();
        return [
            is_array($title) ? 'titles' : 'title' => $title,
            'id' => $this->id,
            'image' => $this->image_link,
            'has_track' => $this->has_track ?? false,
            'number_of_courses' => $courses_count,
        ];
    }
}
