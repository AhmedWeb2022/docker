<?php

namespace App\Modules\Course\Http\Resources\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullSessionResource extends JsonResource
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
            'is_file' => $this->is_file,
            'session_type' => $this->session_type,
            'status' => $this->status,
            'link' => $this->link,
            'image' => $this->image_link,
            'file' => $this->file_link,
            'can_skip' => $this->can_skip,
            'skip_rate' => $this->skip_rate,
        ];
    }
}
