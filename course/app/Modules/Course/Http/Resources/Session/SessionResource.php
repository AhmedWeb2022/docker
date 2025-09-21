<?php

namespace App\Modules\Course\Http\Resources\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
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
            'parent_id' => $this->parent_id,
            'course_id' => $this->course_id,
            'is_free' => $this->is_free,
            'is_standalone' => $this->is_standalone,
            'type' => $this->type,
            'status' => $this->status,
            'price' => $this->price,
            'image' => $this->image,
            'can_skip' => $this->can_skip,
            'skip_rate' => $this->skip_rate,
        ];
    }
}
