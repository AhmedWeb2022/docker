<?php

namespace App\Modules\Diploma\Http\Resources\ContentCourse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "id" => $this->id,
            "course_id"=> $this->course_id,
            "diploma_content_id"=> $this->diploma_content_id,
        ];
    }
}
