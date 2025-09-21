<?php

namespace App\Modules\Course\Http\Resources\CoursePlatform;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Platform\PlatformResource;

class CoursePlatformResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'image' => $this->image_link,
            'cover' => $this->cover_link,
            'link' => $this->link,
            'platform' => new PlatformResource($this->platform),
        ];
    }
}
