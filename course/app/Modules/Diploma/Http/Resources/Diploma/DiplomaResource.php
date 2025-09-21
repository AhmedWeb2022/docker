<?php

namespace App\Modules\Diploma\Http\Resources\Diploma;

use Illuminate\Http\Resources\Json\JsonResource;

class DiplomaResource extends JsonResource
{

    public function toArray($request): array
    {
        $title = $request->header('Accept-Language') !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $short_description = $request->header('Accept-Language') !== "*" ? getTranslation('short_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'short_description');
        $full_description = $request->header('Accept-Language') !== "*" ? getTranslation('full_description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'full_description');
        
        $courses_count = $this->contents->map(function ($content) use ($request) {
            return $content->courses;
        })->flatten()->count();
        $levels_count = $this->levels->count();
        $tracks_count = $this->tracks->count();
        return [
            'id' => $this->id,
            'has_level' => $this->has_level,
            'has_track' => $this->has_track,
            'number_of_courses' => $courses_count,
            'number_of_levels' => $levels_count,
            'number_of_tracks' => $tracks_count,
            'average_rating' => round($this->rates()->avg('rate') ?? 0, 2),
            'reviews_count' => $this->rates()->count() ?? 0,
            is_array($title) ? 'titles' : 'title' => $title,
            is_array($short_description) ? 'descriptions' : 'description' => $short_description,
            is_array($full_description) ? 'card_descriptions' : 'card_description' => $full_description,
            'main_image' => $this->image_link,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'target' => $this->target ?? '',
            'language' => $this->language,
            'diploma_specialization' => $this->diploma_specialization ?? '',
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'targets' => DiplomaTargetResource::collection($this->targets),
            // 'abouts' => DiplomaAboutResource::collection($this->abouts),
        ];
    }
}
