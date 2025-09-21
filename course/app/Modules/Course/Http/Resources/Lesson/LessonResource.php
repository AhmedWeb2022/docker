<?php

namespace App\Modules\Course\Http\Resources\Lesson;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        // dd($this->session_count );
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
            'is_separately_sold' => $this->is_separately_sold,
            "session_count" => $this->session_count != 0 ? $this->session_count : $this->children_session_count,
            "audio_count" => $this->audio_count != 0 ? $this->audio_count : $this->children_audio_count,
            "document_count" => $this->document_count != 0 ? $this->document_count : $this->children_document_count,
            "exam_count" => $this->exam_count != 0 ? $this->exam_count : $this->children_exam_count,
            "children_count" => $this->children_count,
        ];
    }
}
