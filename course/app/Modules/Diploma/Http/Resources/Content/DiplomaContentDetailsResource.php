<?php

namespace App\Modules\Diploma\Http\Resources\Content;

use App\Modules\Course\Http\Resources\Course\CourseWebsiteApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiplomaContentDetailsResource extends ContentResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = parent::toArray($request);

        // $data['courses'] = CourseWebsiteApiResource::collection($this->whenLoaded('courses', $this->courses ?? []));

        return $data;
    }
}
