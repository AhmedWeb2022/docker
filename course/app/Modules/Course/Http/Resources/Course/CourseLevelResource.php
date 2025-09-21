<?php

namespace App\Modules\Course\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Level\LevelResource;

class CourseLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'level' => new LevelResource($this->level),
            'price' => $this->price,
            'payment_status' => $this->payment_status,
            'parent' => new LevelResource($this->level->parent)
        ];
    }
}
