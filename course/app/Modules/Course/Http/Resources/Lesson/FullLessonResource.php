<?php

namespace App\Modules\Course\Http\Resources\Lesson;

use App\Modules\Course\Http\Resources\Content\ContentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullLessonResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'course_id' => $this->course_id,
            'is_free' => $this->is_free,
            'is_standalone' => $this->is_standalone,
            'type' => $this->type,
            'status' => $this->status,
            'price' => $this->price,
            'image' => $this->image_link,
            "session_count" => $this->sessionCount,
            "audio_count" => $this->audioCount,
            "document_count" => $this->documentCount,
            "children_count" => $this->childrenCount,
            'contents' => ContentResource::collection($this->contents),
            'children' => FullLessonResource::collection($this->children),
        ];
    }
}
