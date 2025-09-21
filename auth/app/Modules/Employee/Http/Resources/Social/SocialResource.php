<?php

namespace App\Modules\Employee\Http\Resources\Social;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd(count($this->courses()['data']));
        $data = [
            'id' => $this?->id ?? null,
            'facebook' => $this?->facebook ?? null,
            'twitter' => $this?->twitter ?? null,
            'instagram' => $this?->instagram ?? null,
            'linkedin' => $this?->linkedin ?? null,
            'tiktok' => $this?->tiktok ?? null,
            'whatsapp' => $this?->whatsapp ?? null
        ];
        return $data;
    }
}
