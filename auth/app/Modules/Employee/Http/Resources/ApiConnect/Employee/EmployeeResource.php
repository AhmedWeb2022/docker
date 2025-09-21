<?php

namespace App\Modules\Employee\Http\Resources\ApiConnect\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Employee\Application\Enums\EmployeeTypeEnum;
use App\Modules\Employee\Http\Resources\Social\SocialResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $description = $request->header('Accept-Language')  !== "*" ? getTranslation('description', $request->header('Accept-Language'), $this) : getTranslationAndLocale($this?->translations, 'description');

        $data = [
            is_array($description) ? 'descriptions' : 'description' => $description,
            'id' => $this?->id ?? null,
            'name' => $this?->name ?? null,
            'phone' => $this?->phone ?? null,
            'image' => $this?->image_link ?? null,
            'cover_image' => $this?->cover_image_link ?? null,
            'real_video' => $this?->real_video_link ?? null,
            'email' => $this?->email ?? null,
            'status' => $this?->status ?? null,
            'is_email_verified' => $this?->is_email_verified ?? null,
            'email_verified_at' => $this?->email_verified_at ?? null,
            'is_phone_verified' => $this?->is_phone_verified ?? null,
            'phone_verified_at' => $this?->phone_verified_at ?? null,
            'role' => $this?->role ?? null,
        ];

        return $data;
    }
}
