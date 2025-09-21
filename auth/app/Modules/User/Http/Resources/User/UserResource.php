<?php

namespace App\Modules\User\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $token = ($request->bearerToken()) ? $request->bearerToken() : '';
        $data = [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'image' => $this->image_link ?? null,
            "phone_code" => $this->phone_code ?? null,
            'phone' => $this->phone ?? null,
            'email' => $this->email ?? null,
            'status' => $this->status ?? null,
            'is_email_verified' => $this->is_email_verified ?? null,
            'email_verified_at' => $this->email_verified_at ?? null,
            'is_phone_verified' => $this->is_phone_verified ?? null,
            'phone_verified_at' => $this->phone_verified_at ?? null,
            'stage' => $this->stage ?? null,
            'location' => $this->location ?? null,
            'nationality' => $this->nationality ?? null
        ];

        if ($this?->api_token) {
            $data['api_token'] = $this?->api_token;
        }
        return $data;
    }
}
