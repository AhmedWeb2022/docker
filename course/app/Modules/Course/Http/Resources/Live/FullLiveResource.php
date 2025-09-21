<?php

namespace App\Modules\Course\Http\Resources\Live;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Group\GroupResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use App\Modules\Course\Http\Resources\Course\CourseTitleResource;

class FullLiveResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'image' => $this->image_link,
            'status' => $this->status,
            'duration' => $this->duration,
            'remaining_time' => $this->remaining_time,
            'is_end' => $this->is_end,
            'teacher' => $this->teacher(),
            "group" => new GroupResource($this->group) ?: null,
            "course" => new CourseTitleResource($this->course) ?: null,
        ];
    }
}
