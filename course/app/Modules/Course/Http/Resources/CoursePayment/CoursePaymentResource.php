<?php

namespace App\Modules\Course\Http\Resources\CoursePayment;

use App\Modules\Course\Http\Resources\Currency\CurrencyResource;
use App\Modules\Course\Http\Resources\Video\VideoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursePaymentResource extends JsonResource
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
            'currency' => new CurrencyResource($this->currency),
            'price' => $this->price,
        ];
    }
}
