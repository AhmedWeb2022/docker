<?php

namespace App\Modules\Course\Http\Resources\ContentView\Api;

use App\Modules\Course\Http\Resources\Certificate\Website\CertificateResource;
use App\Modules\Course\Http\Resources\Course\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentViewResource extends JsonResource
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
        ];
    }
}
