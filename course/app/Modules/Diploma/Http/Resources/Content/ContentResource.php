<?php

namespace App\Modules\Diploma\Http\Resources\Content;

use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Course\CourseWebsiteApiResource;
use App\Modules\Course\Http\Resources\Course\DiplomaCourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
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
            'diploma_id' => $this->diploma_id,
            'diploma_level_id' => $this->diploma_level_id,
            'diploma_level_track_id' => $this->diploma_level_track_id,
            'order' => $this->order,
            'courses' => DiplomaCourseResource::collection($this->courses ?? []),
        ];
    }
}
