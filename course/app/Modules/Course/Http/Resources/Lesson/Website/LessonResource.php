<?php

namespace App\Modules\Course\Http\Resources\Lesson\Website;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Content\Website\ContentResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $title = $request->header('Accept-Language')  !== "*" ? getTranslation('title', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'title');

        return [
            'id' => $this->id,
            is_array($title) ? 'titles' : 'title' => $title,
            'number_of_lectures' => $this->contents()->count() ?? 0,
            'number_of_hours' => $this->total_duration ?? 0,
            'is_free' => $this->is_free,
            'price' => $this->price,
            'is_separately_sold' => $this->is_separately_sold,
            'is_standalone' => $this->is_standalone,
            'main_content' => ContentResource::collection($this->contents),
        ];
    }
}
