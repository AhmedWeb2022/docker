<?php

namespace App\Modules\Employee\Http\Resources\Employee;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Employee\Application\Enums\EmployeeTypeEnum;
use App\Modules\Employee\Http\Resources\Social\SocialResource;

class CreateEmployeeResource extends JsonResource
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
            'name' => $this?->name ?? null,
            'phone' => $this?->phone ?? null,
            'image' => $this?->image_link ?? null,
            'email' => $this?->email ?? null,
            'status' => $this?->status ?? null,
            'is_email_verified' => $this?->is_email_verified ?? null,
            'email_verified_at' => $this?->email_verified_at ?? null,
            'is_phone_verified' => $this?->is_phone_verified ?? null,
            'phone_verified_at' => $this?->phone_verified_at ?? null,
            'role' => $this?->role ?? null,
            // 'number_of_course' => count($this->courses()['data']) ?? 0,
            'number_of_student' => 10
        ];
        if ($this?->api_token) {
            $data['api_token'] = $this?->api_token;
        }
        // if ($this->role == EmployeeTypeEnum::TEACHER->value) {
        //     Log::info(['courses' => $this->courses()]);
        //     $courses = $this->courses();
        //     $certificates = $this->certificates();
        //     $subjectStages = $this->subjectStagess();
        //     $stages = $this->stages();
        //     $data['courses'] = $courses['data'] ?? [];
        //     $data['certificates'] = $certificates['data'] ?? [];
        //     $data['stage_subjects'] = $subjectStages['data'] ?? [];
        //     $data['stages'] = $stages['data'] ?? [];
        //     $data['social'] = new SocialResource($this->social);
        // }
        return $data;
    }
}
