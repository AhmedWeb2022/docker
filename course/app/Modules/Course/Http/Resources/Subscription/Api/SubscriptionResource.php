<?php

namespace App\Modules\Course\Http\Resources\Subscription\Api;

use App\Modules\Course\Http\Resources\Certificate\Website\CertificateResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // dd($this->course);

        return [
            'id' => $this->id,
            'user' => $this->user_id != null ? $this->user() : null,
            'course_name' => $this->course->title ?? null,
            'status' => $this->status,
        ];
    }
}
