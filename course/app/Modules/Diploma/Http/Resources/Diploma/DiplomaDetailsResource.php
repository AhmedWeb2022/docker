<?php

namespace App\Modules\Diploma\Http\Resources\Diploma;

use App\Modules\Course\Infrastructure\Persistence\Models\Level\Level;
use App\Modules\Diploma\Http\Resources\Content\ContentResource;
use App\Modules\Diploma\Http\Resources\Content\DiplomaContentDetailsResource;
use App\Modules\Diploma\Http\Resources\DiplomaAbout\DiplomaAboutResource;
use App\Modules\Diploma\Http\Resources\DiplomaTarget\DiplomaTargetResource;
use App\Modules\Diploma\Http\Resources\Level\DiplomaLevelDetailsResource;
use App\Modules\Diploma\Http\Resources\Level\DiplomaLevelResource;
use App\Modules\Diploma\Http\Resources\Track\DiplomaLevelTrackDetailsResource;
use App\Modules\Diploma\Http\Resources\Track\DiplomaTrackResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiplomaDetailsResource extends DiplomaResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);
        return array_merge($data, [
            'levels' => DiplomaLevelDetailsResource::collection($this->levels ?? []),
            'track' => DiplomaLevelTrackDetailsResource::collection($this->tracks ?? []),
            'content' => DiplomaContentDetailsResource::collection($this->contents ?? []),
            'targets' => DiplomaTargetResource::collection($this->targets),
            'abouts' => DiplomaAboutResource::collection($this->abouts),
        ]);
    }
}
