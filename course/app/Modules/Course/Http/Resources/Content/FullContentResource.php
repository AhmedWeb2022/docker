<?php

namespace App\Modules\Course\Http\Resources\Content;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Reference\ReferanceResource;

class FullContentResource extends JsonResource
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
            'image' => $this->image,
            'references' => ReferanceResource::collection($this->referances),
            'children' => FullContentResource::collection($this->children)
        ];
    }
}
