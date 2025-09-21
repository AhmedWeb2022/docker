<?php

namespace App\Modules\Employee\Http\Resources\Employee;

use App\Modules\Employee\Application\Enums\EmployeeTypeEnum;
use App\Modules\Employee\Http\Resources\Social\SocialResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiEmployeeResource extends JsonResource
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
            // 'phone' => $this?->phone ?? null,
            // 'image' => $this?->image_link ?? null,
            // 'email' => $this?->email ?? null,
            // 'status' => $this?->status ?? null,
            // 'is_email_verified' => $this?->is_email_verified ?? null,
            // 'email_verified_at' => $this?->email_verified_at ?? null,
            // 'is_phone_verified' => $this?->is_phone_verified ?? null,
            // 'phone_verified_at' => $this?->phone_verified_at ?? null,
            // 'role' => $this?->role ?? null,
            // 'number_of_course' => count($this->courses()['data']) ?? 0,
        ];
        // if ($this->role == EmployeeTypeEnum::TEACHER->value) {
        //     $data['number_of_student'] = $this->userCount();
        //     $courses = $this->courses();
        //     $data['courses'] = count($courses['data']) ?? [];
        // }

        return $data;
    }
}
