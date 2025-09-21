<?php

namespace App\Modules\Diploma\Http\Resources\Track;

use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Course\DiplomaCourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiplomaTrackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $courses_resources = $this->contents->map(function ($content) {
            return $content->courses;
        })->flatten();
        $title = $request->header('Accept-Language') !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');
        $data = [
            is_array($title) ? 'titles' : 'title' => $title,
            'id' => $this->id,
            'image' => $this->image_link,
            'diploma_id' => $this->diploma_id,
            'diploma_level_id' => $this->diploma_level_id,
        ];
        $data['courses'] = $this->has_track == false ? DiplomaCourseResource::collection($courses_resources) : [];
        return $data;
    }
}
