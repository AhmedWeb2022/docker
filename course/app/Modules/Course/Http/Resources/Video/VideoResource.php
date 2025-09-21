<?php

namespace App\Modules\Course\Http\Resources\Video;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            $this->is_file == 0 ? 'link' : 'video_url' => $this->is_file == 0 ? $this->path : $this->video_link,
            'video_type' => $this->video_type,
        ];
    }
}
