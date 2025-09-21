<?php

namespace App\Modules\Diploma\Http\Resources\Level;

use App\Modules\Course\Http\Resources\Course\DiplomaCourseResource;
use App\Modules\Diploma\Http\Resources\Content\DiplomaContentDetailsResource;
use App\Modules\Diploma\Http\Resources\Track\DiplomaLevelTrackDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiplomaLevelDetailsResource extends DiplomaLevelResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $data = parent::toArray($request);

        /* $data['tracks'] = $this->whenLoaded('tracks', function () use ($request) {
            return DiplomaLevelTrackDetailsResource::collection($this->tracks);
        }); */

        /* $data['contents'] = $this->whenLoaded('contents', function () use ($request) {
            return ContentResource::collection($this->contents);
        }); */

        $data['tracks'] = $this->has_track == true ? DiplomaLevelTrackDetailsResource::collection($this->tracks) : [];
        $data['contents'] = $this->has_track == false ? DiplomaContentDetailsResource::collection($this->contents) : [];
        $courses_resources = $this->contents->map(function ($content) {
            return $content->courses;
        })->flatten();
        $data['courses'] = $this->has_track == false ? DiplomaCourseResource::collection($courses_resources) : [];
        return $data;
    }
}
