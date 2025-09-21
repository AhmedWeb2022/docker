<?php

namespace App\Modules\Course\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Course\Http\Resources\Level\LevelResource;

class CourseOfferResource extends JsonResource
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
            'discount_amount' => $this->discount_amount,
            'discount_from_date' => $this->discount_from_date,
            'discount_to_date' => $this->discount_to_date,
        ];
    }
}
