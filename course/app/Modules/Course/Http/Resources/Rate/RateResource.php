<?php

namespace App\Modules\Course\Http\Resources\Rate;

use App\Modules\Base\Domain\Enums\AuthGurdTypeEnum;
use App\Modules\Base\Domain\Holders\AuthHolder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = AuthHolder::getInstance()->getAuth(AuthGurdTypeEnum::USER->value);

        return [
            'id' => $this->id,
            'rate' => $this->rate ?? null,
            'comment' => $this->comment ?? null,
//             "user_name" => $user?->name ?? null,
//             "user_image" => $user?->image_link ?? null,
            'user_name' => $this->users()['name'] ?? null,
            'user_image' => $this->users()['image'] ? url("storage/". $this->users()['image']) : null
        ];
    }
}
