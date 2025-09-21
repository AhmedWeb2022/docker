<?php

namespace App\Modules\Admin\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'api_token' => $this->api_token,
            'status' => $this->status,
            'is_email_verified' => $this->is_email_verified,
            'email_verified_at' => $this->email_verified_at,
            'is_phone_verified' => $this->is_phone_verified,
            'phone_verified_at' => $this->phone_verified_at,
        ];
    }
}
