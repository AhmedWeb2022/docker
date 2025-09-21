<?php

namespace App\Modules\Diploma\Http\Resources\Track;

use App\Modules\Diploma\Http\Resources\Content\DiplomaContentDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiplomaLevelTrackDetailsResource extends DiplomaTrackResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        /* $data['contents'] = $this->whenLoaded('contents', function () use ($request) {
            return DiplomaContentDetailsResource::collection($this->contents);
        }); */
        $data['contents'] = DiplomaContentDetailsResource::collection($this->contents);
        return $data;
    }
}
